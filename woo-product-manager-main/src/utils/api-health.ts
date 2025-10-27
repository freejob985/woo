import { wooCommerceAPI } from '@/services/api';

interface HealthCheckResult {
  endpoint: string;
  status: 'success' | 'error';
  message: string;
  responseTime?: number;
}

export class APIHealthCheck {
  private results: HealthCheckResult[] = [];

  async checkConnection(): Promise<boolean> {
    const startTime = Date.now();
    try {
      await wooCommerceAPI.getProducts({ per_page: 1 });
      const responseTime = Date.now() - startTime;
      
      this.results.push({
        endpoint: 'GET /products',
        status: 'success',
        message: `✅ Connected successfully (${responseTime}ms)`,
        responseTime,
      });
      
      return true;
    } catch (error) {
      const responseTime = Date.now() - startTime;
      this.results.push({
        endpoint: 'GET /products',
        status: 'error',
        message: `❌ Connection failed: ${error instanceof Error ? error.message : 'Unknown error'}`,
        responseTime,
      });
      
      return false;
    }
  }

  async checkStatsEndpoint(): Promise<boolean> {
    const startTime = Date.now();
    try {
      await wooCommerceAPI.getStats();
      const responseTime = Date.now() - startTime;
      
      this.results.push({
        endpoint: 'GET /products/stats',
        status: 'success',
        message: `✅ Stats endpoint working (${responseTime}ms)`,
        responseTime,
      });
      
      return true;
    } catch (error) {
      const responseTime = Date.now() - startTime;
      this.results.push({
        endpoint: 'GET /products/stats',
        status: 'error',
        message: `❌ Stats endpoint failed: ${error instanceof Error ? error.message : 'Unknown error'}`,
        responseTime,
      });
      
      return false;
    }
  }

  async runFullCheck(): Promise<{ success: boolean; results: HealthCheckResult[] }> {
    this.results = [];
    
    console.log('🔍 Starting API Health Check...\n');
    
    // Check if API is configured
    if (!wooCommerceAPI.isConfigured()) {
      this.results.push({
        endpoint: 'Configuration',
        status: 'error',
        message: '❌ API not configured. Please set VITE_WOOCOMMERCE_API_URL, VITE_WOOCOMMERCE_CONSUMER_KEY, and VITE_WOOCOMMERCE_CONSUMER_SECRET in .env file',
      });
      
      return {
        success: false,
        results: this.results,
      };
    }
    
    this.results.push({
      endpoint: 'Configuration',
      status: 'success',
      message: '✅ API credentials are configured',
    });
    
    // Check connection
    const connectionOk = await this.checkConnection();
    
    // Check stats endpoint
    if (connectionOk) {
      await this.checkStatsEndpoint();
    }
    
    // Print results
    console.log('\n📊 Health Check Results:');
    console.log('========================\n');
    
    this.results.forEach((result) => {
      console.log(`${result.endpoint}: ${result.message}`);
    });
    
    const allSuccess = this.results.every(r => r.status === 'success');
    
    console.log('\n========================');
    console.log(allSuccess ? '✅ All checks passed!' : '❌ Some checks failed');
    console.log('========================\n');
    
    return {
      success: allSuccess,
      results: this.results,
    };
  }

  getResults(): HealthCheckResult[] {
    return this.results;
  }
}

// Export singleton instance
export const apiHealthCheck = new APIHealthCheck();

// Usage:
// import { apiHealthCheck } from '@/utils/api-health';
// await apiHealthCheck.runFullCheck();

