export interface ProductImage {
  id: number;
  src: string;
  thumbnail: string;
  alt?: string;
}

export interface ProductCategory {
  id: number;
  name: string;
  slug: string;
}

export interface ProductTag {
  id: number;
  name: string;
  slug: string;
}

export interface ProductDimensions {
  length: string;
  width: string;
  height: string;
}

export interface ProductAttribute {
  name: string;
  options: string[];
  visible: boolean;
  variation: boolean;
}

export interface ProductVariation {
  id: number;
  sku: string;
  price: string;
  regular_price: string;
  sale_price: string;
  stock_status: "instock" | "outofstock" | "onbackorder";
  stock_quantity: number | null;
  attributes: Record<string, string>;
  image?: ProductImage;
}

export interface Product {
  id: number;
  name: string;
  slug: string;
  permalink: string;
  type: "simple" | "variable";
  status: "publish" | "draft" | "pending";
  featured: boolean;
  catalog_visibility: "visible" | "catalog" | "search" | "hidden";
  description: string;
  short_description: string;
  sku: string;
  price: string;
  price_html: string;
  regular_price: string;
  sale_price: string;
  on_sale: boolean;
  stock_status: "instock" | "outofstock" | "onbackorder";
  stock_quantity: number | null;
  manage_stock: boolean;
  backorders: "no" | "notify" | "yes";
  low_stock_amount: number | null;
  sold_individually: boolean;
  reviews_allowed: boolean;
  average_rating: string;
  rating_count: number;
  total_sales: number;
  is_physical?: boolean;
  weight?: string;
  dimensions?: ProductDimensions;
  shipping_class?: string;
  attributes?: ProductAttribute[];
  variations?: ProductVariation[];
  variations_count?: number;
  price_range?: {
    min: string;
    max: string;
  };
  categories: ProductCategory[];
  tags: ProductTag[];
  images: {
    main_image?: ProductImage;
    gallery: ProductImage[];
  };
  date_created: string;
  date_modified: string;
}

export interface ProductsResponse {
  success: boolean;
  total: number;
  total_products: number;
  total_pages: number;
  current_page: number;
  per_page: number;
  products: Product[];
}

export interface CreateProductData {
  name: string;
  description?: string;
  short_description?: string;
  sku?: string;
  regular_price: string;
  sale_price?: string;
  manage_stock?: boolean;
  stock_quantity?: number | string;
  stock_status?: "instock" | "outofstock" | "onbackorder";
  weight?: string;
  length?: string;
  width?: string;
  height?: string;
  status?: "publish" | "draft";
  featured?: boolean;
  categories?: string;
  tags?: string;
  main_image?: File;
  gallery_images?: File[];
}

export interface CreateVariableProductData extends Omit<CreateProductData, 'regular_price'> {
  attributes: ProductAttribute[];
  variations: Omit<ProductVariation, 'id' | 'image'>[];
}
