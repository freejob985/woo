import axios, { AxiosInstance } from 'axios';
import { Product, ProductsResponse, CreateProductData, CreateVariableProductData } from '@/types/product';

// Custom Error Types
interface CustomError extends Error {
  isCorsError?: boolean;
  isAuthError?: boolean;
  isPermissionError?: boolean;
  isNotFoundError?: boolean;
  isServerError?: boolean;
}

class WooCommerceAPI {
  private api: AxiosInstance;
  private baseURL: string;
  private consumerKey: string;
  private consumerSecret: string;

  constructor() {
    this.baseURL = import.meta.env.VITE_WOOCOMMERCE_API_URL || '';
    this.consumerKey = import.meta.env.VITE_WOOCOMMERCE_CONSUMER_KEY || '';
    this.consumerSecret = import.meta.env.VITE_WOOCOMMERCE_CONSUMER_SECRET || '';

    this.api = axios.create({
      baseURL: this.baseURL,
      auth: {
        username: this.consumerKey,
        password: this.consumerSecret,
      },
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      timeout: 30000,
    });

    // Add request interceptor for error handling
    this.api.interceptors.request.use(
      (config) => {
        console.log(`🚀 API Request: ${config.method?.toUpperCase()} ${config.url}`);
        return config;
      },
      (error) => {
        console.error('❌ Request Error:', error);
        return Promise.reject(error);
      }
    );

    // Add response interceptor for better error messages
    this.api.interceptors.response.use(
      (response) => {
        console.log(`✅ API Response: ${response.config.url}`, response.status);
        return response;
      },
      (error) => {
        // Handle network/CORS errors
        if (error.code === 'ERR_NETWORK' || error.message === 'Network Error') {
          console.error('🚫 CORS Error or Network Issue:', error);
          const corsError = new Error(
            '❌ خطأ CORS - لا يمكن الاتصال بالـ API:\n' +
            '1. تأكد من تفعيل WordPress plugin\n' +
            '2. تحقق من إعدادات CORS في الخادم\n' +
            '3. راجع بيانات API في ملف .env\n\n' +
            'CORS Error: Cannot connect to API. Please ensure:\n' +
            '1. WordPress plugin is activated\n' +
            '2. CORS headers are properly configured (check cors-headers.php)\n' +
            '3. API credentials are correct in .env file\n' +
            '4. Your domain is added to allowed origins list'
          );
          (corsError as CustomError).isCorsError = true;
          throw corsError;
        }
        
        // Handle authentication errors
        if (error.response?.status === 401) {
          const authError = new Error(
            '🔐 خطأ في المصادقة - Authentication failed.\n' +
            'تحقق من Consumer Key و Consumer Secret في ملف .env'
          );
          (authError as CustomError).isAuthError = true;
          throw authError;
        }
        
        // Handle permission errors
        if (error.response?.status === 403) {
          const permError = new Error(
            '🚫 غير مصرح - Permission denied.\n' +
            'تأكد من أن مفاتيح API لديها الصلاحيات الصحيحة'
          );
          (permError as CustomError).isPermissionError = true;
          throw permError;
        }
        
        // Handle 404 errors
        if (error.response?.status === 404) {
          const notFoundError = new Error(
            '❓ لم يتم العثور على المورد - Resource not found.\n' +
            'تحقق من صحة الـ endpoint و ID المطلوب'
          );
          (notFoundError as CustomError).isNotFoundError = true;
          throw notFoundError;
        }
        
        // Handle server errors
        if (error.response?.status >= 500) {
          const serverError = new Error(
            '⚠️ خطأ في الخادم - Server error.\n' +
            'يرجى المحاولة مرة أخرى لاحقاً أو التواصل مع الدعم الفني'
          );
          (serverError as CustomError).isServerError = true;
          throw serverError;
        }
        
        // Log all errors for debugging
        console.error('❌ API Error:', {
          url: error.config?.url,
          method: error.config?.method,
          status: error.response?.status,
          message: error.message,
          data: error.response?.data
        });
        
        return Promise.reject(error);
      }
    );
  }

  // Check if API is configured
  isConfigured(): boolean {
    return !!(this.baseURL && this.consumerKey && this.consumerSecret);
  }

  // Update API credentials
  updateCredentials(apiUrl: string, consumerKey: string, consumerSecret: string) {
    this.baseURL = apiUrl;
    this.consumerKey = consumerKey;
    this.consumerSecret = consumerSecret;

    this.api = axios.create({
      baseURL: this.baseURL,
      auth: {
        username: this.consumerKey,
        password: this.consumerSecret,
      },
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      timeout: 30000,
    });

    // Re-apply interceptors
    this.api.interceptors.request.use(
      (config) => {
        console.log(`🚀 API Request: ${config.method?.toUpperCase()} ${config.url}`);
        return config;
      },
      (error) => {
        console.error('❌ Request Error:', error);
        return Promise.reject(error);
      }
    );

    this.api.interceptors.response.use(
      (response) => {
        console.log(`✅ API Response: ${response.config.url}`, response.status);
        return response;
      },
      (error) => {
        // Handle network/CORS errors
        if (error.code === 'ERR_NETWORK' || error.message === 'Network Error') {
          console.error('🚫 CORS Error or Network Issue:', error);
          const corsError = new Error(
            '❌ خطأ CORS - لا يمكن الاتصال بالـ API:\n' +
            '1. تأكد من تفعيل WordPress plugin\n' +
            '2. تحقق من إعدادات CORS في الخادم\n' +
            '3. راجع بيانات API في ملف .env\n\n' +
            'CORS Error: Cannot connect to API. Please ensure:\n' +
            '1. WordPress plugin is activated\n' +
            '2. CORS headers are properly configured (check cors-headers.php)\n' +
            '3. API credentials are correct in .env file\n' +
            '4. Your domain is added to allowed origins list'
          );
          (corsError as CustomError).isCorsError = true;
          throw corsError;
        }
        
        // Handle authentication errors
        if (error.response?.status === 401) {
          const authError = new Error(
            '🔐 خطأ في المصادقة - Authentication failed.\n' +
            'تحقق من Consumer Key و Consumer Secret في ملف .env'
          );
          (authError as CustomError).isAuthError = true;
          throw authError;
        }
        
        // Handle permission errors
        if (error.response?.status === 403) {
          const permError = new Error(
            '🚫 غير مصرح - Permission denied.\n' +
            'تأكد من أن مفاتيح API لديها الصلاحيات الصحيحة'
          );
          (permError as CustomError).isPermissionError = true;
          throw permError;
        }
        
        // Handle 404 errors
        if (error.response?.status === 404) {
          const notFoundError = new Error(
            '❓ لم يتم العثور على المورد - Resource not found.\n' +
            'تحقق من صحة الـ endpoint و ID المطلوب'
          );
          (notFoundError as CustomError).isNotFoundError = true;
          throw notFoundError;
        }
        
        // Handle server errors
        if (error.response?.status >= 500) {
          const serverError = new Error(
            '⚠️ خطأ في الخادم - Server error.\n' +
            'يرجى المحاولة مرة أخرى لاحقاً أو التواصل مع الدعم الفني'
          );
          (serverError as CustomError).isServerError = true;
          throw serverError;
        }
        
        // Log all errors for debugging
        console.error('❌ API Error:', {
          url: error.config?.url,
          method: error.config?.method,
          status: error.response?.status,
          message: error.message,
          data: error.response?.data
        });
        
        return Promise.reject(error);
      }
    );
  }

  // Get all products with filters
  async getProducts(params?: {
    page?: number;
    per_page?: number;
    type?: 'all' | 'physical' | 'variable';
    status?: 'publish' | 'draft' | 'any';
    featured?: boolean;
    on_sale?: boolean;
    category?: string;
    orderby?: 'date' | 'title' | 'price' | 'popularity' | 'rating';
    order?: 'ASC' | 'DESC';
  }): Promise<ProductsResponse> {
    const response = await this.api.get('/products', { params });
    return response.data;
  }

  // Search products
  async searchProducts(searchTerm: string, params?: {
    page?: number;
    per_page?: number;
    type?: 'all' | 'physical' | 'variable';
  }): Promise<ProductsResponse> {
    const response = await this.api.get('/products/search', {
      params: { s: searchTerm, ...params },
    });
    return response.data;
  }

  // Get single product
  async getProduct(id: number): Promise<Product> {
    const response = await this.api.get(`/products/${id}`);
    return response.data.product;
  }

  // Create physical product
  async createPhysicalProduct(data: CreateProductData): Promise<Product> {
    const formData = new FormData();
    
    Object.entries(data).forEach(([key, value]) => {
      if (value !== undefined && value !== null) {
        if (key === 'gallery_images' && Array.isArray(value)) {
          value.forEach((file) => {
            formData.append('gallery_images[]', file);
          });
        } else if (key === 'main_image' && value instanceof File) {
          formData.append('main_image', value);
        } else {
          formData.append(key, value.toString());
        }
      }
    });

    const response = await this.api.post('/physical-products', formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    });
    return response.data.product;
  }

  // Create variable product
  async createVariableProduct(data: CreateVariableProductData): Promise<Product> {
    const formData = new FormData();
    
    Object.entries(data).forEach(([key, value]) => {
      if (value !== undefined && value !== null) {
        if (key === 'attributes' || key === 'variations') {
          formData.append(key, JSON.stringify(value));
        } else if (key === 'gallery_images' && Array.isArray(value)) {
          value.forEach((file) => {
            formData.append('gallery_images[]', file);
          });
        } else if (key === 'main_image' && value instanceof File) {
          formData.append('main_image', value);
        } else {
          formData.append(key, value.toString());
        }
      }
    });

    const response = await this.api.post('/variable-products', formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    });
    return response.data.product;
  }

  // Update physical product
  async updatePhysicalProduct(id: number, data: Partial<CreateProductData>): Promise<Product> {
    const formData = new FormData();
    
    Object.entries(data).forEach(([key, value]) => {
      if (value !== undefined && value !== null) {
        if (key === 'gallery_images' && Array.isArray(value)) {
          value.forEach((file) => {
            formData.append('gallery_images[]', file);
          });
        } else if (key === 'main_image' && value instanceof File) {
          formData.append('main_image', value);
        } else {
          formData.append(key, value.toString());
        }
      }
    });

    const response = await this.api.post(`/physical-products/${id}`, formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    });
    return response.data.product;
  }

  // Update variable product
  async updateVariableProduct(id: number, data: Partial<CreateVariableProductData>): Promise<Product> {
    const formData = new FormData();
    
    Object.entries(data).forEach(([key, value]) => {
      if (value !== undefined && value !== null) {
        if (key === 'attributes' || key === 'variations') {
          formData.append(key, JSON.stringify(value));
        } else if (key === 'gallery_images' && Array.isArray(value)) {
          value.forEach((file) => {
            formData.append('gallery_images[]', file);
          });
        } else if (key === 'main_image' && value instanceof File) {
          formData.append('main_image', value);
        } else {
          formData.append(key, value.toString());
        }
      }
    });

    const response = await this.api.post(`/variable-products/${id}`, formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    });
    return response.data.product;
  }

  // Delete product
  async deleteProduct(id: number, type: 'physical' | 'variable'): Promise<void> {
    const endpoint = type === 'physical' ? 'physical-products' : 'variable-products';
    await this.api.delete(`/${endpoint}/${id}`);
  }

  // Get statistics
  async getStats(): Promise<Record<string, unknown>> {
    const response = await this.api.get('/products/stats');
    return response.data.statistics;
  }
}

export const wooCommerceAPI = new WooCommerceAPI();
