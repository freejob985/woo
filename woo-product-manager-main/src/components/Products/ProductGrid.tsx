import React, { useEffect } from 'react';
import { useProducts } from '@/contexts/ProductContext';
import ProductCard from './ProductCard';
import { Button } from '@/components/ui/button';
import { Loader2, AlertCircle } from 'lucide-react';
import { Alert, AlertDescription } from '@/components/ui/alert';

interface ProductGridProps {
  onDelete: (id: number) => void;
  onEdit: (product: any, section: string) => void;
}

const ProductGrid: React.FC<ProductGridProps> = ({ onDelete, onEdit }) => {
  const { products, loading, error, pagination, fetchProducts } = useProducts();

  useEffect(() => {
    fetchProducts();
  }, []);

  if (loading && products.length === 0) {
    return (
      <div className="flex items-center justify-center h-64">
        <Loader2 className="h-8 w-8 animate-spin text-primary" />
      </div>
    );
  }

  if (error) {
    return (
      <Alert variant="destructive">
        <AlertCircle className="h-4 w-4" />
        <AlertDescription>{error}</AlertDescription>
      </Alert>
    );
  }

  if (products.length === 0) {
    return (
      <div className="text-center py-12">
        <p className="text-muted-foreground">No products found</p>
      </div>
    );
  }

  return (
    <div className="space-y-6">
      {/* Products Grid */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        {products.map((product) => (
          <ProductCard
            key={product.id}
            product={product}
            onDelete={onDelete}
            onEdit={onEdit}
          />
        ))}
      </div>

      {/* Pagination */}
      {pagination.totalPages > 1 && (
        <div className="flex items-center justify-center gap-2">
          <Button
            variant="outline"
            size="sm"
            disabled={pagination.currentPage === 1 || loading}
            onClick={() => fetchProducts({ page: pagination.currentPage - 1 })}
          >
            Previous
          </Button>
          <span className="text-sm text-muted-foreground">
            Page {pagination.currentPage} of {pagination.totalPages}
          </span>
          <Button
            variant="outline"
            size="sm"
            disabled={pagination.currentPage === pagination.totalPages || loading}
            onClick={() => fetchProducts({ page: pagination.currentPage + 1 })}
          >
            Next
          </Button>
        </div>
      )}
    </div>
  );
};

export default ProductGrid;
