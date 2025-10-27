import React, { createContext, useContext, useState, useCallback } from 'react';
import { wooCommerceAPI } from '@/services/api';

interface ProductStats {
  products_overview?: {
    total_products?: number;
    physical_products?: number;
    variable_products?: number;
    total_variations?: number;
  };
  stock_status?: {
    in_stock?: number;
    out_of_stock?: number;
  };
  sales_info?: {
    total_sales?: number;
    on_sale?: number;
    featured?: number;
    average_sales_per_product?: number;
  };
}

interface StatsContextType {
  stats: ProductStats | null;
  loading: boolean;
  refreshStats: () => Promise<void>;
}

const StatsContext = createContext<StatsContextType | undefined>(undefined);

export const StatsProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  const [stats, setStats] = useState<ProductStats | null>(null);
  const [loading, setLoading] = useState(false);

  const refreshStats = useCallback(async () => {
    if (!wooCommerceAPI.isConfigured()) {
      return;
    }

    setLoading(true);
    try {
      const data = await wooCommerceAPI.getStats();
      console.log('📊 Stats refreshed:', data);
      setStats(data);
    } catch (error) {
      console.error('Failed to refresh stats:', error);
    } finally {
      setLoading(false);
    }
  }, []);

  // Auto-load stats on mount and listen for refresh events
  React.useEffect(() => {
    if (wooCommerceAPI.isConfigured()) {
      refreshStats();
    }

    // Listen for refresh stats event
    const handleRefreshStats = () => {
      console.log('📊 Stats refresh triggered by event');
      refreshStats();
    };

    window.addEventListener('refreshStats', handleRefreshStats);
    return () => {
      window.removeEventListener('refreshStats', handleRefreshStats);
    };
  }, [refreshStats]);

  return (
    <StatsContext.Provider value={{ stats, loading, refreshStats }}>
      {children}
    </StatsContext.Provider>
  );
};

export const useStats = () => {
  const context = useContext(StatsContext);
  if (context === undefined) {
    throw new Error('useStats must be used within a StatsProvider');
  }
  return context;
};

