import React, { useState, useEffect } from 'react';
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
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { wooCommerceAPI } from '@/services/api';
import { useToast } from '@/hooks/use-toast';
import { useProducts } from '@/contexts/ProductContext';
import { Loader2 } from 'lucide-react';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { Product } from '@/types/product';
import { Badge } from '@/components/ui/badge';

interface EditProductModalProps {
  open: boolean;
  onClose: () => void;
  product: Product | null;
  section: string;
}

const EditProductModal: React.FC<EditProductModalProps> = ({ open, onClose, product, section }) => {
  const [loading, setLoading] = useState(false);
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
    stock_status: 'instock' as 'instock' | 'outofstock',
    manage_stock: true,
    featured: false,
    status: 'publish' as 'publish' | 'draft',
    catalog_visibility: 'visible' as 'visible' | 'catalog' | 'search' | 'hidden',
    weight: '',
    length: '',
    width: '',
    height: '',
    shipping_class: '',
  });

  const [mainImage, setMainImage] = useState<File | null>(null);
  const [galleryImages, setGalleryImages] = useState<File[]>([]);

  useEffect(() => {
    if (product) {
      setFormData({
        name: product.name || '',
        description: product.description || '',
        short_description: product.short_description || '',
        sku: product.sku || '',
        regular_price: product.regular_price || '',
        sale_price: product.sale_price || '',
        stock_quantity: product.stock_quantity?.toString() || '',
        stock_status: product.stock_status || 'instock',
        manage_stock: product.manage_stock || false,
        featured: product.featured || false,
        status: product.status || 'publish',
        catalog_visibility: product.catalog_visibility || 'visible',
        weight: product.weight || '',
        length: product.dimensions?.length || '',
        width: product.dimensions?.width || '',
        height: product.dimensions?.height || '',
        shipping_class: product.shipping_class || '',
      });
    }
  }, [product]);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!product) return;

    setLoading(true);

    try {
      const updateData: any = {
        ...formData,
      };

      if (mainImage) {
        updateData.main_image = mainImage;
      }

      if (galleryImages.length > 0) {
        updateData.gallery_images = galleryImages;
      }

      if (product.type === 'variable') {
        await wooCommerceAPI.updateVariableProduct(product.id, updateData);
      } else {
        await wooCommerceAPI.updatePhysicalProduct(product.id, updateData);
      }

      toast({
        title: 'Success',
        description: 'Product updated successfully',
      });

      await refreshProducts();
      onClose();
    } catch (err: any) {
      toast({
        title: 'Error',
        description: err.response?.data?.message || 'Failed to update product',
        variant: 'destructive',
      });
    } finally {
      setLoading(false);
    }
  };

  const getSectionTitle = () => {
    switch (section) {
      case 'general':
        return 'General Information';
      case 'variations':
        return 'Variations';
      case 'inventory':
        return 'Inventory Management';
      case 'shipping':
        return 'Shipping Details';
      case 'linked':
        return 'Linked Products';
      case 'media':
        return 'Product Media';
      case 'settings':
        return 'Publishing Settings';
      default:
        return 'Edit Product';
    }
  };

  if (!product) return null;

  return (
    <Dialog open={open} onOpenChange={onClose}>
      <DialogContent className="max-w-3xl max-h-[90vh] overflow-y-auto">
        <DialogHeader>
          <DialogTitle>{getSectionTitle()}</DialogTitle>
          <DialogDescription>
            Editing: {product.name}
          </DialogDescription>
        </DialogHeader>

        <form onSubmit={handleSubmit} className="space-y-6">
          {/* General Section */}
          {section === 'general' && (
            <div className="space-y-4">
              <div className="space-y-2">
                <Label htmlFor="name">Product Name</Label>
                <Input
                  id="name"
                  value={formData.name}
                  onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                />
              </div>

              <div className="space-y-2">
                <Label htmlFor="description">Description</Label>
                <Textarea
                  id="description"
                  value={formData.description}
                  onChange={(e) => setFormData({ ...formData, description: e.target.value })}
                  rows={4}
                />
              </div>

              <div className="space-y-2">
                <Label htmlFor="short_description">Short Description</Label>
                <Textarea
                  id="short_description"
                  value={formData.short_description}
                  onChange={(e) => setFormData({ ...formData, short_description: e.target.value })}
                  rows={2}
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
                  <Label htmlFor="regular_price">Regular Price</Label>
                  <Input
                    id="regular_price"
                    type="number"
                    step="0.01"
                    value={formData.regular_price}
                    onChange={(e) => setFormData({ ...formData, regular_price: e.target.value })}
                  />
                </div>
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
          )}

          {/* Variations Section */}
          {section === 'variations' && product.type === 'variable' && (
            <div className="space-y-4">
              <div className="p-4 bg-muted rounded-lg">
                <h4 className="font-semibold mb-2">Product Variations</h4>
                <p className="text-sm text-muted-foreground mb-4">
                  Total Variations: {product.variations_count || 0}
                </p>
                {product.variations && product.variations.length > 0 ? (
                  <div className="space-y-3">
                    {product.variations.map((variation, index) => (
                      <div key={variation.id} className="p-3 border rounded-md bg-background">
                        <div className="flex items-center justify-between mb-2">
                          <div className="flex gap-2">
                            {Object.entries(variation.attributes).map(([key, value]) => (
                              <Badge key={key} variant="secondary">
                                {key}: {value}
                              </Badge>
                            ))}
                          </div>
                          <span className="font-semibold">{variation.price} ر.س</span>
                        </div>
                        <div className="text-xs text-muted-foreground flex gap-4">
                          <span>SKU: {variation.sku || 'N/A'}</span>
                          <span>Stock: {variation.stock_quantity || 'N/A'}</span>
                          <span className={variation.stock_status === 'instock' ? 'text-green-600' : 'text-red-600'}>
                            {variation.stock_status === 'instock' ? 'In Stock' : 'Out of Stock'}
                          </span>
                        </div>
                      </div>
                    ))}
                  </div>
                ) : (
                  <p className="text-sm text-muted-foreground">No variations found.</p>
                )}
              </div>
              <p className="text-xs text-muted-foreground">
                Note: To edit individual variations, please use the WooCommerce admin panel for advanced control.
              </p>
            </div>
          )}

          {/* Inventory Section */}
          {section === 'inventory' && (
            <div className="space-y-4">
              <div className="flex items-center space-x-2">
                <Switch
                  id="manage_stock"
                  checked={formData.manage_stock}
                  onCheckedChange={(checked) => setFormData({ ...formData, manage_stock: checked })}
                />
                <Label htmlFor="manage_stock">Manage Stock</Label>
              </div>

              {formData.manage_stock && (
                <div className="space-y-2">
                  <Label htmlFor="stock_quantity">Stock Quantity</Label>
                  <Input
                    id="stock_quantity"
                    type="number"
                    value={formData.stock_quantity}
                    onChange={(e) => setFormData({ ...formData, stock_quantity: e.target.value })}
                  />
                </div>
              )}

              <div className="space-y-2">
                <Label htmlFor="stock_status">Stock Status</Label>
                <Select
                  value={formData.stock_status}
                  onValueChange={(value: any) => setFormData({ ...formData, stock_status: value })}
                >
                  <SelectTrigger>
                    <SelectValue />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="instock">In Stock</SelectItem>
                    <SelectItem value="outofstock">Out of Stock</SelectItem>
                    <SelectItem value="onbackorder">On Backorder</SelectItem>
                  </SelectContent>
                </Select>
              </div>
            </div>
          )}

          {/* Shipping Section */}
          {section === 'shipping' && product.type !== 'variable' && (
            <div className="space-y-4">
              <div className="space-y-2">
                <Label htmlFor="weight">Weight (kg)</Label>
                <Input
                  id="weight"
                  type="number"
                  step="0.01"
                  value={formData.weight}
                  onChange={(e) => setFormData({ ...formData, weight: e.target.value })}
                />
              </div>

              <div className="grid grid-cols-3 gap-4">
                <div className="space-y-2">
                  <Label htmlFor="length">Length (cm)</Label>
                  <Input
                    id="length"
                    type="number"
                    step="0.01"
                    value={formData.length}
                    onChange={(e) => setFormData({ ...formData, length: e.target.value })}
                  />
                </div>

                <div className="space-y-2">
                  <Label htmlFor="width">Width (cm)</Label>
                  <Input
                    id="width"
                    type="number"
                    step="0.01"
                    value={formData.width}
                    onChange={(e) => setFormData({ ...formData, width: e.target.value })}
                  />
                </div>

                <div className="space-y-2">
                  <Label htmlFor="height">Height (cm)</Label>
                  <Input
                    id="height"
                    type="number"
                    step="0.01"
                    value={formData.height}
                    onChange={(e) => setFormData({ ...formData, height: e.target.value })}
                  />
                </div>
              </div>

              <div className="space-y-2">
                <Label htmlFor="shipping_class">Shipping Class</Label>
                <Input
                  id="shipping_class"
                  value={formData.shipping_class}
                  onChange={(e) => setFormData({ ...formData, shipping_class: e.target.value })}
                  placeholder="e.g., express, standard"
                />
              </div>
            </div>
          )}

          {/* Linked Products Section */}
          {section === 'linked' && (
            <div className="space-y-4">
              <div className="p-4 bg-muted rounded-lg">
                <p className="text-sm text-muted-foreground">
                  Linked products (Upsells, Cross-sells) management is available through the WooCommerce admin panel for advanced configuration.
                </p>
              </div>
            </div>
          )}

          {/* Media Section */}
          {section === 'media' && (
            <div className="space-y-4">
              <div className="space-y-2">
                <Label>Current Main Image</Label>
                {product.images?.main_image?.src && (
                  <img
                    src={product.images.main_image.src}
                    alt={product.name}
                    className="w-32 h-32 object-cover rounded-md border"
                  />
                )}
              </div>

              <div className="space-y-2">
                <Label htmlFor="main_image">Upload New Main Image</Label>
                <Input
                  id="main_image"
                  type="file"
                  accept="image/*"
                  onChange={(e) => setMainImage(e.target.files?.[0] || null)}
                />
              </div>

              <div className="space-y-2">
                <Label>Current Gallery Images</Label>
                {product.images?.gallery && product.images.gallery.length > 0 ? (
                  <div className="grid grid-cols-4 gap-2">
                    {product.images.gallery.map((img, index) => (
                      <img
                        key={index}
                        src={img.thumbnail}
                        alt={`Gallery ${index + 1}`}
                        className="w-full h-20 object-cover rounded-md border"
                      />
                    ))}
                  </div>
                ) : (
                  <p className="text-sm text-muted-foreground">No gallery images</p>
                )}
              </div>

              <div className="space-y-2">
                <Label htmlFor="gallery_images">Upload New Gallery Images</Label>
                <Input
                  id="gallery_images"
                  type="file"
                  accept="image/*"
                  multiple
                  onChange={(e) => setGalleryImages(Array.from(e.target.files || []))}
                />
              </div>
            </div>
          )}

          {/* Publishing Settings Section */}
          {section === 'settings' && (
            <div className="space-y-4">
              <div className="space-y-2">
                <Label htmlFor="status">Status</Label>
                <Select
                  value={formData.status}
                  onValueChange={(value: any) => setFormData({ ...formData, status: value })}
                >
                  <SelectTrigger>
                    <SelectValue />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="publish">Published</SelectItem>
                    <SelectItem value="draft">Draft</SelectItem>
                    <SelectItem value="pending">Pending Review</SelectItem>
                    <SelectItem value="private">Private</SelectItem>
                  </SelectContent>
                </Select>
              </div>

              <div className="space-y-2">
                <Label htmlFor="catalog_visibility">Catalog Visibility</Label>
                <Select
                  value={formData.catalog_visibility}
                  onValueChange={(value: any) => setFormData({ ...formData, catalog_visibility: value })}
                >
                  <SelectTrigger>
                    <SelectValue />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="visible">Shop and search results</SelectItem>
                    <SelectItem value="catalog">Shop only</SelectItem>
                    <SelectItem value="search">Search results only</SelectItem>
                    <SelectItem value="hidden">Hidden</SelectItem>
                  </SelectContent>
                </Select>
              </div>

              <div className="flex items-center space-x-2">
                <Switch
                  id="featured"
                  checked={formData.featured}
                  onCheckedChange={(checked) => setFormData({ ...formData, featured: checked })}
                />
                <Label htmlFor="featured">Featured Product</Label>
              </div>

              <div className="p-4 bg-muted rounded-lg">
                <h4 className="font-semibold mb-2">Categories</h4>
                {product.categories && product.categories.length > 0 ? (
                  <div className="flex gap-2 flex-wrap">
                    {product.categories.map((cat) => (
                      <Badge key={cat.id} variant="secondary">
                        {cat.name}
                      </Badge>
                    ))}
                  </div>
                ) : (
                  <p className="text-sm text-muted-foreground">No categories assigned</p>
                )}
              </div>
            </div>
          )}

          {/* Action Buttons */}
          <div className="flex justify-end gap-2 pt-4 border-t">
            <Button type="button" variant="outline" onClick={onClose} disabled={loading}>
              Cancel
            </Button>
            <Button type="submit" disabled={loading}>
              {loading && <Loader2 className="mr-2 h-4 w-4 animate-spin" />}
              Save Changes
            </Button>
          </div>
        </form>
      </DialogContent>
    </Dialog>
  );
};

export default EditProductModal;

