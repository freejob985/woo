import React, { useState } from 'react';
import DashboardLayout from '@/components/Layout/DashboardLayout';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { wooCommerceAPI } from '@/services/api';
import { useToast } from '@/hooks/use-toast';
import { Save } from 'lucide-react';

const Settings = () => {
  const { toast } = useToast();
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

            <Button onClick={handleSave} className="w-full">
              <Save className="h-4 w-4 mr-2" />
              Save Settings
            </Button>
          </CardContent>
        </Card>
      </div>
    </DashboardLayout>
  );
};

export default Settings;
