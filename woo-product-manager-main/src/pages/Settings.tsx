import React, { useState } from 'react';
import DashboardLayout from '@/components/Layout/DashboardLayout';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
import { wooCommerceAPI } from '@/services/api';
import { useToast } from '@/hooks/use-toast';
import { apiHealthCheck } from '@/utils/api-health';
import { Save, CheckCircle2, AlertCircle, Loader2 } from 'lucide-react';

interface HealthCheckResult {
  endpoint: string;
  status: 'success' | 'error';
  message: string;
  responseTime?: number;
}

interface TestResults {
  success: boolean;
  results: HealthCheckResult[];
}

const Settings = () => {
  const { toast } = useToast();
  const [testing, setTesting] = useState(false);
  const [testResults, setTestResults] = useState<TestResults | null>(null);
  
  const [apiUrl, setApiUrl] = useState(
    import.meta.env.VITE_WOOCOMMERCE_API_URL || ''
  );
  const [consumerKey, setConsumerKey] = useState(
    import.meta.env.VITE_WOOCOMMERCE_CONSUMER_KEY || ''
  );
  const [consumerSecret, setConsumerSecret] = useState(
    import.meta.env.VITE_WOOCOMMERCE_CONSUMER_SECRET || ''
  );

  const handleSave = () => {
    wooCommerceAPI.updateCredentials(apiUrl, consumerKey, consumerSecret);
    toast({
      title: 'Settings Saved',
      description: 'API credentials have been updated successfully',
    });
    setTestResults(null);
  };

  const handleTestConnection = async () => {
    setTesting(true);
    setTestResults(null);
    
    try {
      // Update credentials temporarily for testing
      wooCommerceAPI.updateCredentials(apiUrl, consumerKey, consumerSecret);
      
      const results = await apiHealthCheck.runFullCheck();
      setTestResults(results);
      
      if (results.success) {
        toast({
          title: '✅ اتصال ناجح / Connection Successful',
          description: 'جميع endpoints تعمل بشكل صحيح / All endpoints are working correctly',
        });
      } else {
        toast({
          title: '❌ فشل الاتصال / Connection Failed',
          description: 'يرجى التحقق من البيانات والمحاولة مرة أخرى / Please check your credentials and try again',
          variant: 'destructive',
        });
      }
    } catch (error) {
      toast({
        title: 'Test Failed',
        description: error instanceof Error ? error.message : 'Unknown error occurred',
        variant: 'destructive',
      });
    } finally {
      setTesting(false);
    }
  };

  return (
    <DashboardLayout>
      <div className="p-6 max-w-4xl">
        <div className="mb-6">
          <h1 className="text-3xl font-bold">Settings</h1>
          <p className="text-muted-foreground">
            Configure your WooCommerce API connection
          </p>
        </div>

        <Card>
          <CardHeader>
            <CardTitle>WooCommerce API Configuration</CardTitle>
            <CardDescription>
              Enter your WooCommerce REST API credentials to connect to your store
            </CardDescription>
          </CardHeader>
          <CardContent className="space-y-4">
            <div className="space-y-2">
              <Label htmlFor="api_url">API URL</Label>
              <Input
                id="api_url"
                value={apiUrl}
                onChange={(e) => setApiUrl(e.target.value)}
                placeholder="https://your-store.com/wp-json/murjan-api/v1"
              />
              <p className="text-xs text-muted-foreground">
                The base URL for your WooCommerce API
              </p>
            </div>

            <div className="space-y-2">
              <Label htmlFor="consumer_key">Consumer Key</Label>
              <Input
                id="consumer_key"
                value={consumerKey}
                onChange={(e) => setConsumerKey(e.target.value)}
                placeholder="ck_xxxxxxxxxxxxx"
              />
            </div>

            <div className="space-y-2">
              <Label htmlFor="consumer_secret">Consumer Secret</Label>
              <Input
                id="consumer_secret"
                type="password"
                value={consumerSecret}
                onChange={(e) => setConsumerSecret(e.target.value)}
                placeholder="cs_xxxxxxxxxxxxx"
              />
            </div>

            <div className="bg-muted p-4 rounded-lg">
              <h3 className="font-semibold mb-2 text-sm">How to get API credentials:</h3>
              <ol className="text-sm space-y-1 list-decimal list-inside text-muted-foreground">
                <li>Go to your WordPress admin dashboard</li>
                <li>Navigate to WooCommerce → Settings → Advanced → REST API</li>
                <li>Click "Add Key" to create a new API key</li>
                <li>Copy the Consumer Key and Consumer Secret</li>
              </ol>
            </div>

            <div className="flex gap-2">
              <Button onClick={handleSave} className="flex-1">
                <Save className="h-4 w-4 mr-2" />
                Save Settings
              </Button>
              <Button
                onClick={handleTestConnection}
                disabled={testing || !apiUrl || !consumerKey || !consumerSecret}
                variant="outline"
                className="flex-1"
              >
                {testing ? (
                  <Loader2 className="h-4 w-4 mr-2 animate-spin" />
                ) : (
                  <CheckCircle2 className="h-4 w-4 mr-2" />
                )}
                {testing ? 'Testing...' : 'Test Connection'}
              </Button>
            </div>

            {testResults && (
              <Alert variant={testResults.success ? 'default' : 'destructive'} className="mt-4">
                {testResults.success ? (
                  <CheckCircle2 className="h-4 w-4" />
                ) : (
                  <AlertCircle className="h-4 w-4" />
                )}
                <AlertTitle>
                  {testResults.success ? '✅ All Tests Passed' : '❌ Tests Failed'}
                </AlertTitle>
                <AlertDescription>
                  <div className="mt-2 space-y-1 text-sm">
                    {testResults.results.map((result, index) => (
                      <div key={index} className="flex items-start gap-2">
                        <span className={result.status === 'success' ? 'text-green-600' : 'text-red-600'}>
                          {result.status === 'success' ? '✓' : '✗'}
                        </span>
                        <div className="flex-1">
                          <span className="font-semibold">{result.endpoint}:</span>{' '}
                          {result.message}
                        </div>
                      </div>
                    ))}
                  </div>
                </AlertDescription>
              </Alert>
            )}
          </CardContent>
        </Card>

        <Card className="mt-6">
          <CardHeader>
            <CardTitle>🔧 خطوات الإعداد السريع / Quick Setup Guide</CardTitle>
          </CardHeader>
          <CardContent className="space-y-4">
            <div className="space-y-2">
              <h3 className="font-semibold text-sm">1️⃣ تفعيل WordPress Plugin:</h3>
              <ul className="list-disc list-inside text-sm text-muted-foreground space-y-1">
                <li>ارفع مجلد <code className="bg-muted px-1 rounded">api/</code> إلى <code className="bg-muted px-1 rounded">wp-content/plugins/</code></li>
                <li>فعّل الـ plugin من لوحة WordPress</li>
                <li>تأكد من تفعيل WooCommerce</li>
              </ul>
            </div>

            <div className="space-y-2">
              <h3 className="font-semibold text-sm">2️⃣ إصلاح CORS:</h3>
              <ul className="list-disc list-inside text-sm text-muted-foreground space-y-1">
                <li>انسخ <code className="bg-muted px-1 rounded">api/cors-headers.php</code> إلى <code className="bg-muted px-1 rounded">wp-content/mu-plugins/</code></li>
                <li>أو فعّله تلقائياً من خلال الـ plugin (مفعّل افتراضياً)</li>
                <li>أضف domain الخاص بك في قائمة allowed_origins</li>
              </ul>
            </div>

            <div className="space-y-2">
              <h3 className="font-semibold text-sm">3️⃣ إنشاء API Keys:</h3>
              <ul className="list-disc list-inside text-sm text-muted-foreground space-y-1">
                <li>WooCommerce → Settings → Advanced → REST API</li>
                <li>Add Key → Read/Write Permissions</li>
                <li>انسخ Consumer Key و Consumer Secret</li>
              </ul>
            </div>

            <div className="bg-blue-50 dark:bg-blue-950 p-3 rounded-lg">
              <p className="text-sm font-semibold mb-1">💡 نصيحة مهمة:</p>
              <p className="text-xs text-muted-foreground">
                استخدم زر "Test Connection" للتأكد من صحة الإعدادات قبل البدء
              </p>
            </div>
          </CardContent>
        </Card>
      </div>
    </DashboardLayout>
  );
};

export default Settings;
