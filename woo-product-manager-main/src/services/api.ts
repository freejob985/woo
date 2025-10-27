import axios, { AxiosInstance } from 'axios';
import { Product, ProductsResponse, CreateProductData, CreateVariableProductData } from '@/types/product';

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
      },
    });
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
      },
    });
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
  async getStats(): Promise<any> {
    const response = await this.api.get('/products/stats');
    return response.data.statistics;
  }
}

export const wooCommerceAPI = new WooCommerceAPI();
