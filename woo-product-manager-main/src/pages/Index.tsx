import React, { useState } from 'react';
import DashboardLayout from '@/components/Layout/DashboardLayout';
import ProductGrid from '@/components/Products/ProductGrid';
import AddProductModal from '@/components/Products/AddProductModal';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Plus, Search } from 'lucide-react';
import { useProducts } from '@/contexts/ProductContext';
import { Product } from '@/types/product';
import { useToast } from '@/hooks/use-toast';

const Index = () => {
  const [addModalOpen, setAddModalOpen] = useState(false);
  const [searchTerm, setSearchTerm] = useState('');
  const { searchProducts, deleteProduct } = useProducts();
  const { toast } = useToast();

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
    toast({
      title: 'Edit Feature',
      description: `Opening ${section} editor for ${product.name}`,
    });
    // TODO: Implement edit modals
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
          <Button onClick={() => setAddModalOpen(true)}>
            <Plus className="h-4 w-4 mr-2" />
            Add Product
          </Button>
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

        {/* Add Product Modal */}
        <AddProductModal open={addModalOpen} onClose={() => setAddModalOpen(false)} />
      </div>
    </DashboardLayout>
  );
};

export default Index;
