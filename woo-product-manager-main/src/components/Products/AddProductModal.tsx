import React, { useState } from 'react';
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Switch } from '@/components/ui/switch';
import { wooCommerceAPI } from '@/services/api';
import { useToast } from '@/hooks/use-toast';
import { useProducts } from '@/contexts/ProductContext';
import { Loader2, Package, Boxes } from 'lucide-react';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';

interface AddProductModalProps {
  open: boolean;
  onClose: () => void;
}

const AddProductModal: React.FC<AddProductModalProps> = ({ open, onClose }) => {
  const [loading, setLoading] = useState(false);
  const [productType, setProductType] = useState<'simple' | 'variable'>('simple');
  const { toast } = useToast();
  const { refreshProducts } = useProducts();

  const [formData, setFormData] = useState({
    name: '',
    description: '',
    short_description: '',
    sku: '',
    regular_price: '',
    sale_price: '',
    stock_quantity: '',
    categories: '',
    manage_stock: true,
    stock_status: 'instock' as 'instock' | 'outofstock',
    featured: false,
  });

  const [mainImage, setMainImage] = useState<File | null>(null);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);

    try {
      const productData = {
        ...formData,
        main_image: mainImage || undefined,
        status: 'publish' as 'publish' | 'draft',
      };

      if (productType === 'simple') {
        await wooCommerceAPI.createPhysicalProduct(productData);
      } else {
        // For variable products, we need attributes and variations
        await wooCommerceAPI.createVariableProduct({
          ...productData,
          attributes: [
            {
              name: 'Size',
              options: ['S', 'M', 'L'],
              visible: true,
              variation: true,
            },
          ],
          variations: [
            {
              attributes: { Size: 'S' },
              sku: `${formData.sku}-S`,
              price: formData.regular_price,
              regular_price: formData.regular_price,
              sale_price: formData.sale_price,
              stock_status: formData.stock_status,
              stock_quantity: parseInt(formData.stock_quantity) || null,
            },
          ],
        });
      }

      toast({
        title: 'Success',
        description: 'Product created successfully',
      });

      await refreshProducts();
      onClose();
      resetForm();
    } catch (err: any) {
      toast({
        title: 'Error',
        description: err.response?.data?.message || 'Failed to create product',
        variant: 'destructive',
      });
    } finally {
      setLoading(false);
    }
  };

  const resetForm = () => {
    setFormData({
      name: '',
      description: '',
      short_description: '',
      sku: '',
      regular_price: '',
      sale_price: '',
      stock_quantity: '',
      categories: '',
      manage_stock: true,
      stock_status: 'instock',
      featured: false,
    });
    setMainImage(null);
  };

  return (
    <Dialog open={open} onOpenChange={onClose}>
      <DialogContent className="max-w-2xl max-h-[90vh] overflow-y-auto">
        <DialogHeader>
          <DialogTitle>Add New Product</DialogTitle>
          <DialogDescription>
            Create a new product in your WooCommerce store
          </DialogDescription>
        </DialogHeader>

        <Tabs value={productType} onValueChange={(v) => setProductType(v as any)}>
          <TabsList className="grid w-full grid-cols-2">
            <TabsTrigger value="simple">
              <Package className="h-4 w-4 mr-2" />
              Simple Product
            </TabsTrigger>
            <TabsTrigger value="variable">
              <Boxes className="h-4 w-4 mr-2" />
              Variable Product
            </TabsTrigger>
          </TabsList>

          <TabsContent value="simple" className="space-y-4 mt-4">
            <form onSubmit={handleSubmit} className="space-y-4">
              <div className="space-y-2">
                <Label htmlFor="name">Product Name *</Label>
                <Input
                  id="name"
                  value={formData.name}
                  onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                  required
                />
              </div>

              <div className="space-y-2">
                <Label htmlFor="description">Description</Label>
                <Textarea
                  id="description"
                  value={formData.description}
                  onChange={(e) => setFormData({ ...formData, description: e.target.value })}
                  rows={3}
                />
              </div>

              <div className="grid grid-cols-2 gap-4">
                <div className="space-y-2">
                  <Label htmlFor="sku">SKU</Label>
                  <Input
                    id="sku"
                    value={formData.sku}
                    onChange={(e) => setFormData({ ...formData, sku: e.target.value })}
                  />
                </div>

                <div className="space-y-2">
                  <Label htmlFor="categories">Categories</Label>
                  <Input
                    id="categories"
                    placeholder="category1,category2"
                    value={formData.categories}
                    onChange={(e) => setFormData({ ...formData, categories: e.target.value })}
                  />
                </div>
              </div>

              <div className="grid grid-cols-2 gap-4">
                <div className="space-y-2">
                  <Label htmlFor="regular_price">Regular Price *</Label>
                  <Input
                    id="regular_price"
                    type="number"
                    step="0.01"
                    value={formData.regular_price}
                    onChange={(e) => setFormData({ ...formData, regular_price: e.target.value })}
                    required
                  />
                </div>

                <div className="space-y-2">
                  <Label htmlFor="sale_price">Sale Price</Label>
                  <Input
                    id="sale_price"
                    type="number"
                    step="0.01"
                    value={formData.sale_price}
                    onChange={(e) => setFormData({ ...formData, sale_price: e.target.value })}
                  />
                </div>
              </div>

              <div className="space-y-2">
                <Label htmlFor="stock_quantity">Stock Quantity</Label>
                <Input
                  id="stock_quantity"
                  type="number"
                  value={formData.stock_quantity}
                  onChange={(e) => setFormData({ ...formData, stock_quantity: e.target.value })}
                />
              </div>

              <div className="space-y-2">
                <Label htmlFor="main_image">Product Image</Label>
                <Input
                  id="main_image"
                  type="file"
                  accept="image/*"
                  onChange={(e) => setMainImage(e.target.files?.[0] || null)}
                />
              </div>

              <div className="flex items-center space-x-2">
                <Switch
                  id="featured"
                  checked={formData.featured}
                  onCheckedChange={(checked) => setFormData({ ...formData, featured: checked })}
                />
                <Label htmlFor="featured">Featured Product</Label>
              </div>

              <div className="flex justify-end gap-2 pt-4">
                <Button type="button" variant="outline" onClick={onClose}>
                  Cancel
                </Button>
                <Button type="submit" disabled={loading}>
                  {loading && <Loader2 className="mr-2 h-4 w-4 animate-spin" />}
                  Create Product
                </Button>
              </div>
            </form>
          </TabsContent>

          <TabsContent value="variable" className="space-y-4 mt-4">
            <form onSubmit={handleSubmit} className="space-y-4">
              <p className="text-sm text-muted-foreground">
                Variable product creation with default Size attribute (S, M, L).
                You can customize variations after creation.
              </p>

              <div className="space-y-2">
                <Label htmlFor="name">Product Name *</Label>
                <Input
                  id="name"
                  value={formData.name}
                  onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                  required
                />
              </div>

              <div className="grid grid-cols-2 gap-4">
                <div className="space-y-2">
                  <Label htmlFor="regular_price">Regular Price *</Label>
                  <Input
                    id="regular_price"
                    type="number"
                    step="0.01"
                    value={formData.regular_price}
                    onChange={(e) => setFormData({ ...formData, regular_price: e.target.value })}
                    required
                  />
                </div>

                <div className="space-y-2">
                  <Label htmlFor="stock_quantity">Stock Quantity</Label>
                  <Input
                    id="stock_quantity"
                    type="number"
                    value={formData.stock_quantity}
                    onChange={(e) => setFormData({ ...formData, stock_quantity: e.target.value })}
                  />
                </div>
              </div>

              <div className="space-y-2">
                <Label htmlFor="main_image">Product Image</Label>
                <Input
                  id="main_image"
                  type="file"
                  accept="image/*"
                  onChange={(e) => setMainImage(e.target.files?.[0] || null)}
                />
              </div>

              <div className="flex justify-end gap-2 pt-4">
                <Button type="button" variant="outline" onClick={onClose}>
                  Cancel
                </Button>
                <Button type="submit" disabled={loading}>
                  {loading && <Loader2 className="mr-2 h-4 w-4 animate-spin" />}
                  Create Variable Product
                </Button>
              </div>
            </form>
          </TabsContent>
        </Tabs>
      </DialogContent>
    </Dialog>
  );
};

export default AddProductModal;
