import React, { useState, useEffect } from 'react';
import DashboardLayout from '@/components/Layout/DashboardLayout';
import ProductGrid from '@/components/Products/ProductGrid';
import AddProductModal from '@/components/Products/AddProductModal';
import EditProductModal from '@/components/Products/EditProductModal';
import { ApiSetupModal } from '@/components/ApiSetup/ApiSetupModal';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Plus, Search, Settings } from 'lucide-react';
import { useProducts } from '@/contexts/ProductContext';
import { Product } from '@/types/product';
import { useToast } from '@/hooks/use-toast';
import { wooCommerceAPI } from '@/services/api';

const Index = () => {
  const [addModalOpen, setAddModalOpen] = useState(false);
  const [editModalOpen, setEditModalOpen] = useState(false);
  const [apiSetupOpen, setApiSetupOpen] = useState(false);
  const [isApiConfigured, setIsApiConfigured] = useState(false);
  const [selectedProduct, setSelectedProduct] = useState<Product | null>(null);
  const [editSection, setEditSection] = useState<string>('general');
  const [searchTerm, setSearchTerm] = useState('');
  const { searchProducts, deleteProduct, refreshProducts } = useProducts();
  const { toast } = useToast();

  // Check if API is configured on mount
  useEffect(() => {
    const checkApiConfig = () => {
      const configured = wooCommerceAPI.isConfigured();
      setIsApiConfigured(configured);
      if (!configured) {
        setApiSetupOpen(true);
      }
    };
    checkApiConfig();
  }, []);

  const handleSearch = (e: React.FormEvent) => {
    e.preventDefault();
    if (searchTerm.trim()) {
      searchProducts(searchTerm);
    }
  };

  const handleDelete = async (id: number) => {
    if (confirm('Are you sure you want to delete this product?')) {
      try {
        await deleteProduct(id, 'physical');
      } catch (error) {
        // Error already handled in context
      }
    }
  };

  const handleEdit = (product: Product, section: string) => {
    setSelectedProduct(product);
    setEditSection(section);
    setEditModalOpen(true);
  };

  const handleApiSetupSuccess = () => {
    setIsApiConfigured(true);
    refreshProducts();
    toast({
      title: "✅ تم الاتصال بنجاح",
      description: "تم حفظ بيانات API والاتصال بالمتجر بنجاح",
    });
  };

  return (
    <DashboardLayout>
      <div className="p-6 space-y-6">
        {/* Header */}
        <div className="flex items-center justify-between">
          <div>
            <h1 className="text-3xl font-bold">Products</h1>
            <p className="text-muted-foreground">
              Manage your WooCommerce products
            </p>
          </div>
          <div className="flex gap-2">
            <Button
              variant="outline"
              size="icon"
              onClick={() => setApiSetupOpen(true)}
              title="API Settings"
            >
              <Settings className="h-4 w-4" />
            </Button>
            <Button onClick={() => setAddModalOpen(true)} disabled={!isApiConfigured}>
              <Plus className="h-4 w-4 mr-2" />
              Add Product
            </Button>
          </div>
        </div>

        {/* Search Bar */}
        <form onSubmit={handleSearch} className="flex gap-2 max-w-md">
          <Input
            placeholder="Search products..."
            value={searchTerm}
            onChange={(e) => setSearchTerm(e.target.value)}
          />
          <Button type="submit" variant="secondary">
            <Search className="h-4 w-4" />
          </Button>
        </form>

        {/* Products Grid */}
        <ProductGrid onDelete={handleDelete} onEdit={handleEdit} />

        {/* API Setup Modal */}
        <ApiSetupModal
          open={apiSetupOpen}
          onClose={() => {
            // Only allow closing if API is configured
            if (isApiConfigured) {
              setApiSetupOpen(false);
            }
          }}
          onSuccess={handleApiSetupSuccess}
        />

        {/* Add Product Modal */}
        <AddProductModal open={addModalOpen} onClose={() => setAddModalOpen(false)} />

        {/* Edit Product Modal */}
        <EditProductModal
          open={editModalOpen}
          onClose={() => {
            setEditModalOpen(false);
            setSelectedProduct(null);
          }}
          product={selectedProduct}
          section={editSection}
        />
      </div>
    </DashboardLayout>
  );
};

export default Index;
