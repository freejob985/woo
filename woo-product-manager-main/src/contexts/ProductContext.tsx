import React, { createContext, useContext, useState, useCallback } from 'react';
import { Product, ProductsResponse } from '@/types/product';
import { wooCommerceAPI } from '@/services/api';
import { useToast } from '@/hooks/use-toast';

interface ProductContextType {
  products: Product[];
  loading: boolean;
  error: string | null;
  pagination: {
    currentPage: number;
    totalPages: number;
    totalProducts: number;
    perPage: number;
  };
  fetchProducts: (params?: any) => Promise<void>;
  searchProducts: (searchTerm: string) => Promise<void>;
  refreshProducts: () => Promise<void>;
  deleteProduct: (id: number, type: 'physical' | 'variable') => Promise<void>;
}

const ProductContext = createContext<ProductContextType | undefined>(undefined);

export const ProductProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  const [products, setProducts] = useState<Product[]>([]);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);
  const [pagination, setPagination] = useState({
    currentPage: 1,
    totalPages: 1,
    totalProducts: 0,
    perPage: parseInt(import.meta.env.VITE_ITEMS_PER_PAGE || '12'),
  });
  const { toast } = useToast();

  const fetchProducts = useCallback(async (params?: any) => {
    if (!wooCommerceAPI.isConfigured()) {
      setError('API not configured. Please configure your WooCommerce API credentials.');
      return;
    }

    setLoading(true);
    setError(null);
    try {
      const response: ProductsResponse = await wooCommerceAPI.getProducts({
        per_page: pagination.perPage,
        ...params,
      });
      setProducts(response.products);
      setPagination({
        currentPage: response.current_page,
        totalPages: response.total_pages,
        totalProducts: response.total_products,
        perPage: response.per_page,
      });
    } catch (err: any) {
      const errorMessage = err.response?.data?.message || 'Failed to fetch products';
      setError(errorMessage);
      toast({
        title: 'Error',
        description: errorMessage,
        variant: 'destructive',
      });
    } finally {
      setLoading(false);
    }
  }, [pagination.perPage, toast]);

  const searchProducts = useCallback(async (searchTerm: string) => {
    if (!wooCommerceAPI.isConfigured()) {
      setError('API not configured');
      return;
    }

    setLoading(true);
    setError(null);
    try {
      const response: ProductsResponse = await wooCommerceAPI.searchProducts(searchTerm, {
        per_page: pagination.perPage,
      });
      setProducts(response.products);
      setPagination({
        currentPage: response.current_page,
        totalPages: response.total_pages,
        totalProducts: response.total_products,
        perPage: response.per_page,
      });
    } catch (err: any) {
      const errorMessage = err.response?.data?.message || 'Search failed';
      setError(errorMessage);
      toast({
        title: 'Error',
        description: errorMessage,
        variant: 'destructive',
      });
    } finally {
      setLoading(false);
    }
  }, [pagination.perPage, toast]);

  const refreshProducts = useCallback(async () => {
    await fetchProducts({ page: pagination.currentPage });
  }, [fetchProducts, pagination.currentPage]);

  const deleteProduct = useCallback(async (id: number, type: 'physical' | 'variable') => {
    try {
      await wooCommerceAPI.deleteProduct(id, type);
      toast({
        title: 'Success',
        description: 'Product deleted successfully',
      });
      await refreshProducts();
    } catch (err: any) {
      const errorMessage = err.response?.data?.message || 'Failed to delete product';
      toast({
        title: 'Error',
        description: errorMessage,
        variant: 'destructive',
      });
      throw err;
    }
  }, [toast, refreshProducts]);

  return (
    <ProductContext.Provider
      value={{
        products,
        loading,
        error,
        pagination,
        fetchProducts,
        searchProducts,
        refreshProducts,
        deleteProduct,
      }}
    >
      {children}
    </ProductContext.Provider>
  );
};

export const useProducts = () => {
  const context = useContext(ProductContext);
  if (context === undefined) {
    throw new Error('useProducts must be used within a ProductProvider');
  }
  return context;
};
