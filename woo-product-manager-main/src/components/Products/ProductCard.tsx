import React, { useState } from 'react';
import { Product } from '@/types/product';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import {
  Package,
  Edit,
  Trash2,
  MoreVertical,
  Settings,
  ImageIcon,
  Boxes,
  Truck,
  Link as LinkIcon,
  Loader2,
  Save as SaveIcon,
} from 'lucide-react';
import { wooCommerceAPI } from '@/services/api';
import { useToast } from '@/hooks/use-toast';
import { useProducts } from '@/contexts/ProductContext';

interface ProductCardProps {
  product: Product;
  onDelete: (id: number) => void;
  onEdit: (product: Product, section: string) => void;
}

const ProductCard: React.FC<ProductCardProps> = ({ product, onDelete, onEdit }) => {
  const [imageError, setImageError] = useState(false);
  const [saving, setSaving] = useState(false);
  const { toast } = useToast();
  const { refreshProducts } = useProducts();

  const stockStatusColor = {
    instock: 'text-success',
    outofstock: 'text-destructive',
    onbackorder: 'text-warning',
  };

  const productTypeLabel = product.type === 'variable' ? 'Variable' : 'Simple';
  const availability = product.stock_status === 'instock' ? 'In Stock' : 'Out of Stock';

  const handleSave = async () => {
    setSaving(true);
    try {
      // This is a quick save - it doesn't change anything but confirms the product is synced
      // In a real scenario, you would track changes and only save modified fields
      if (product.type === 'variable') {
        await wooCommerceAPI.updateVariableProduct(product.id, {
          name: product.name,
          status: product.status,
        });
      } else {
        await wooCommerceAPI.updatePhysicalProduct(product.id, {
          name: product.name,
          status: product.status,
        });
      }
      
      toast({
        title: 'Success',
        description: 'Product saved successfully',
      });
      
      await refreshProducts();
    } catch (err: any) {
      toast({
        title: 'Error',
        description: err.message || 'Failed to save product',
        variant: 'destructive',
      });
    } finally {
      setSaving(false);
    }
  };

  return (
    <Card className="group hover:shadow-lg transition-shadow duration-200 bg-dashboard-card hover:bg-dashboard-card-hover">
      <CardContent className="p-4 space-y-3">
        {/* Row 1: Thumbnail | Category | Type */}
        <div className="flex items-start justify-between gap-3">
          <div className="w-16 h-16 rounded-md bg-muted flex items-center justify-center overflow-hidden flex-shrink-0">
            {product.images?.main_image?.thumbnail && !imageError ? (
              <img
                src={product.images.main_image.thumbnail}
                alt={product.name}
                className="w-full h-full object-cover"
                onError={() => setImageError(true)}
              />
            ) : (
              <Package className="h-6 w-6 text-muted-foreground" />
            )}
          </div>
          
          <div className="flex-1 min-w-0">
            <div className="flex items-center gap-2 flex-wrap">
              {product.categories[0] && (
                <Badge variant="outline" className="text-xs">
                  {product.categories[0].name}
                </Badge>
              )}
              <Badge variant="secondary" className="text-xs">
                {productTypeLabel}
              </Badge>
            </div>
          </div>
        </div>

        {/* Row 2: Name | Availability | Price */}
        <div className="space-y-2">
          <h3 className="font-semibold text-sm line-clamp-2 leading-tight">
            {product.name}
          </h3>
          <div className="flex items-center justify-between text-xs">
            <span className={stockStatusColor[product.stock_status]}>
              {availability}
            </span>
            <span className="font-semibold text-sm">
              {product.price_html ? (
                <span dangerouslySetInnerHTML={{ __html: product.price_html }} />
              ) : (
                `${product.price} ر.س`
              )}
            </span>
          </div>
        </div>

        {/* Rows 3-4: Edit Modals Icons */}
        <div className="grid grid-cols-3 gap-2">
          <Button
            variant="outline"
            size="sm"
            className="text-xs"
            onClick={() => onEdit(product, 'general')}
          >
            <Edit className="h-3 w-3 mr-1" />
            General
          </Button>
          <Button
            variant="outline"
            size="sm"
            className="text-xs"
            onClick={() => onEdit(product, 'inventory')}
          >
            <Boxes className="h-3 w-3 mr-1" />
            Inventory
          </Button>
          <Button
            variant="outline"
            size="sm"
            className="text-xs"
            onClick={() => onEdit(product, 'shipping')}
          >
            <Truck className="h-3 w-3 mr-1" />
            Shipping
          </Button>
          <Button
            variant="outline"
            size="sm"
            className="text-xs"
            onClick={() => onEdit(product, 'linked')}
          >
            <LinkIcon className="h-3 w-3 mr-1" />
            Linked
          </Button>
          <Button
            variant="outline"
            size="sm"
            className="text-xs"
            onClick={() => onEdit(product, 'media')}
          >
            <ImageIcon className="h-3 w-3 mr-1" />
            Media
          </Button>
          {product.type === 'variable' && (
            <Button
              variant="outline"
              size="sm"
              className="text-xs"
              onClick={() => onEdit(product, 'variations')}
            >
              <Package className="h-3 w-3 mr-1" />
              Variations
            </Button>
          )}
        </div>

        {/* Row 5: Actions */}
        <div className="flex items-center justify-between gap-2 pt-2 border-t">
          <Button 
            variant="default" 
            size="sm" 
            className="flex-1"
            onClick={handleSave}
            disabled={saving}
          >
            {saving ? (
              <>
                <Loader2 className="h-3 w-3 mr-1 animate-spin" />
                Saving...
              </>
            ) : (
              <>
                <SaveIcon className="h-3 w-3 mr-1" />
                Save
              </>
            )}
          </Button>
          
          <DropdownMenu>
            <DropdownMenuTrigger asChild>
              <Button variant="outline" size="sm">
                <Settings className="h-4 w-4" />
              </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent align="end">
              <DropdownMenuItem onClick={() => onEdit(product, 'settings')}>
                <Settings className="h-4 w-4 mr-2" />
                Publishing Settings
              </DropdownMenuItem>
              <DropdownMenuSeparator />
              <DropdownMenuItem
                className="text-destructive"
                onClick={() => onDelete(product.id)}
              >
                <Trash2 className="h-4 w-4 mr-2" />
                Delete Product
              </DropdownMenuItem>
            </DropdownMenuContent>
          </DropdownMenu>
        </div>

        {product.sku && (
          <div className="text-xs text-muted-foreground">
            SKU: {product.sku}
          </div>
        )}
      </CardContent>
    </Card>
  );
};

export default ProductCard;
