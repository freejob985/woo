import React, { useEffect, useState, useCallback } from 'react';
import DashboardLayout from '@/components/Layout/DashboardLayout';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { wooCommerceAPI } from '@/services/api';
import { Package, TrendingUp, AlertCircle, Loader2, RefreshCw } from 'lucide-react';
import { useToast } from '@/hooks/use-toast';

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

const Stats = () => {
  const [stats, setStats] = useState<ProductStats | null>(null);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);
  const { toast } = useToast();

  const fetchStats = useCallback(async (showRefreshingState = false) => {
    try {
      if (showRefreshingState) {
        setRefreshing(true);
      } else {
        setLoading(true);
      }
      
      const data = await wooCommerceAPI.getStats();
      console.log('📊 Stats loaded:', data);
      setStats(data);
      
      if (showRefreshingState) {
        toast({
          title: 'تم التحديث',
          description: 'تم تحديث الإحصائيات بنجاح',
        });
      }
    } catch (error) {
      const errorMessage = error instanceof Error ? error.message : 'فشل تحميل الإحصائيات';
      console.error('Failed to fetch stats:', error);
      toast({
        title: 'خطأ',
        description: errorMessage,
        variant: 'destructive',
      });
    } finally {
      setLoading(false);
      setRefreshing(false);
    }
  }, [toast]);

  useEffect(() => {
    if (wooCommerceAPI.isConfigured()) {
      fetchStats();
    } else {
      setLoading(false);
    }

    // Listen for refresh stats event from ProductContext
    const handleRefreshStats = () => {
      console.log('📊 Stats refresh triggered by product change');
      fetchStats(true);
    };

    window.addEventListener('refreshStats', handleRefreshStats);
    return () => {
      window.removeEventListener('refreshStats', handleRefreshStats);
    };
  }, [fetchStats]);

  if (loading) {
    return (
      <DashboardLayout>
        <div className="flex items-center justify-center h-screen">
          <Loader2 className="h-8 w-8 animate-spin text-primary" />
        </div>
      </DashboardLayout>
    );
  }

  if (!stats) {
    return (
      <DashboardLayout>
        <div className="p-6">
          <div className="flex items-center gap-2 text-muted-foreground">
            <AlertCircle className="h-5 w-5" />
            <p>Unable to load statistics. Please check your API configuration.</p>
          </div>
        </div>
      </DashboardLayout>
    );
  }

  return (
    <DashboardLayout>
      <div className="p-6 space-y-6">
        <div className="flex items-center justify-between">
          <div>
            <h1 className="text-3xl font-bold">Statistics</h1>
            <p className="text-muted-foreground">
              Overview of your WooCommerce store
            </p>
          </div>
          <Button
            onClick={() => fetchStats(true)}
            disabled={refreshing || loading}
            variant="outline"
            size="sm"
          >
            <RefreshCw className={`h-4 w-4 mr-2 ${refreshing ? 'animate-spin' : ''}`} />
            {refreshing ? 'جاري التحديث...' : 'تحديث'}
          </Button>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
          <Card>
            <CardHeader className="flex flex-row items-center justify-between pb-2">
              <CardTitle className="text-sm font-medium">Total Products</CardTitle>
              <Package className="h-4 w-4 text-muted-foreground" />
            </CardHeader>
            <CardContent>
              <div className="text-2xl font-bold">
                {stats.products_overview?.total_products || 0}
              </div>
            </CardContent>
          </Card>

          <Card>
            <CardHeader className="flex flex-row items-center justify-between pb-2">
              <CardTitle className="text-sm font-medium">In Stock</CardTitle>
              <TrendingUp className="h-4 w-4 text-success" />
            </CardHeader>
            <CardContent>
              <div className="text-2xl font-bold">
                {stats.stock_status?.in_stock || 0}
              </div>
            </CardContent>
          </Card>

          <Card>
            <CardHeader className="flex flex-row items-center justify-between pb-2">
              <CardTitle className="text-sm font-medium">Out of Stock</CardTitle>
              <AlertCircle className="h-4 w-4 text-destructive" />
            </CardHeader>
            <CardContent>
              <div className="text-2xl font-bold">
                {stats.stock_status?.out_of_stock || 0}
              </div>
            </CardContent>
          </Card>

          <Card>
            <CardHeader className="flex flex-row items-center justify-between pb-2">
              <CardTitle className="text-sm font-medium">Total Sales</CardTitle>
              <TrendingUp className="h-4 w-4 text-muted-foreground" />
            </CardHeader>
            <CardContent>
              <div className="text-2xl font-bold">
                {stats.sales_info?.total_sales || 0}
              </div>
            </CardContent>
          </Card>
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-2 gap-4">
          <Card>
            <CardHeader>
              <CardTitle>Product Types</CardTitle>
            </CardHeader>
            <CardContent className="space-y-2">
              <div className="flex justify-between">
                <span>Physical Products</span>
                <span className="font-semibold">
                  {stats.products_overview?.physical_products || 0}
                </span>
              </div>
              <div className="flex justify-between">
                <span>Variable Products</span>
                <span className="font-semibold">
                  {stats.products_overview?.variable_products || 0}
                </span>
              </div>
              <div className="flex justify-between">
                <span>Total Variations</span>
                <span className="font-semibold">
                  {stats.products_overview?.total_variations || 0}
                </span>
              </div>
            </CardContent>
          </Card>

          <Card>
            <CardHeader>
              <CardTitle>Sales Info</CardTitle>
            </CardHeader>
            <CardContent className="space-y-2">
              <div className="flex justify-between">
                <span>On Sale</span>
                <span className="font-semibold">
                  {stats.sales_info?.on_sale || 0}
                </span>
              </div>
              <div className="flex justify-between">
                <span>Featured</span>
                <span className="font-semibold">
                  {stats.sales_info?.featured || 0}
                </span>
              </div>
              <div className="flex justify-between">
                <span>Average Sales/Product</span>
                <span className="font-semibold">
                  {stats.sales_info?.average_sales_per_product?.toFixed(2) || 0}
                </span>
              </div>
            </CardContent>
          </Card>
        </div>
      </div>
    </DashboardLayout>
  );
};

export default Stats;
