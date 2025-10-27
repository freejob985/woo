import React, { useEffect, useState } from 'react';
import DashboardLayout from '@/components/Layout/DashboardLayout';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { wooCommerceAPI } from '@/services/api';
import { Package, TrendingUp, AlertCircle, Loader2 } from 'lucide-react';

const Stats = () => {
  const [stats, setStats] = useState<any>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchStats = async () => {
      try {
        const data = await wooCommerceAPI.getStats();
        setStats(data);
      } catch (error) {
        console.error('Failed to fetch stats:', error);
      } finally {
        setLoading(false);
      }
    };

    if (wooCommerceAPI.isConfigured()) {
      fetchStats();
    } else {
      setLoading(false);
    }
  }, []);

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
        <div>
          <h1 className="text-3xl font-bold">Statistics</h1>
          <p className="text-muted-foreground">
            Overview of your WooCommerce store
          </p>
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
