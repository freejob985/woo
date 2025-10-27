<?php
/**
 * All Products API Class
 * Handles REST API endpoints for all products (physical + variable) with pagination and search
 */

if (!defined('ABSPATH')) {
    exit;
}

class WOO_All_Products_API {
    
    /**
     * Register REST API routes
     */
    public function register_routes() {
        // Get all products (physical + variable) with pagination
        register_rest_route(WOO_PRODUCTS_API_NAMESPACE, '/products', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_all_products'),
            'permission_callback' => array('WOO_Products_API_Authentication', 'check_permission'),
            'args' => array(
                'page' => array(
                    'default' => 1,
                    'sanitize_callback' => 'absint',
                    'description' => 'رقم الصفحة الحالية'
                ),
                'per_page' => array(
                    'default' => 10,
                    'sanitize_callback' => 'absint',
                    'description' => 'عدد المنتجات في كل صفحة'
                ),
                'orderby' => array(
                    'default' => 'date',
                    'sanitize_callback' => 'sanitize_text_field',
                    'description' => 'ترتيب حسب: date, title, price, popularity, rating'
                ),
                'order' => array(
                    'default' => 'DESC',
                    'sanitize_callback' => 'sanitize_text_field',
                    'description' => 'اتجاه الترتيب: ASC أو DESC'
                ),
                'type' => array(
                    'default' => 'all',
                    'sanitize_callback' => 'sanitize_text_field',
                    'description' => 'نوع المنتج: all, physical, variable'
                ),
                'status' => array(
                    'default' => 'publish',
                    'sanitize_callback' => 'sanitize_text_field',
                    'description' => 'حالة المنتج: publish, draft, any'
                ),
                'featured' => array(
                    'sanitize_callback' => 'rest_sanitize_boolean',
                    'description' => 'المنتجات المميزة فقط'
                ),
                'on_sale' => array(
                    'sanitize_callback' => 'rest_sanitize_boolean',
                    'description' => 'المنتجات المخفضة فقط'
                ),
                'category' => array(
                    'sanitize_callback' => 'sanitize_text_field',
                    'description' => 'فلترة حسب التصنيف (slug)'
                )
            )
        ));
        
        // Search all products with pagination
        register_rest_route(WOO_PRODUCTS_API_NAMESPACE, '/products/search', array(
            'methods' => 'GET',
            'callback' => array($this, 'search_products'),
            'permission_callback' => array('WOO_Products_API_Authentication', 'check_permission'),
            'args' => array(
                's' => array(
                    'required' => true,
                    'sanitize_callback' => 'sanitize_text_field',
                    'description' => 'كلمة البحث'
                ),
                'page' => array(
                    'default' => 1,
                    'sanitize_callback' => 'absint',
                    'description' => 'رقم الصفحة'
                ),
                'per_page' => array(
                    'default' => 10,
                    'sanitize_callback' => 'absint',
                    'description' => 'عدد النتائج في الصفحة'
                ),
                'type' => array(
                    'default' => 'all',
                    'sanitize_callback' => 'sanitize_text_field',
                    'description' => 'نوع المنتج: all, physical, variable'
                ),
                'orderby' => array(
                    'default' => 'relevance',
                    'sanitize_callback' => 'sanitize_text_field',
                    'description' => 'ترتيب حسب: relevance, date, title, price'
                )
            )
        ));
        
        // Get single product (any type)
        register_rest_route(WOO_PRODUCTS_API_NAMESPACE, '/products/(?P<id>\d+)', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_single_product'),
            'permission_callback' => array('WOO_Products_API_Authentication', 'check_permission')
        ));
        
        // Get products statistics
        register_rest_route(WOO_PRODUCTS_API_NAMESPACE, '/products/stats', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_statistics'),
            'permission_callback' => array('WOO_Products_API_Authentication', 'check_permission')
        ));
    }
    
    /**
     * Get all products with advanced pagination and filtering
     */
    public function get_all_products($request) {
        $page = $request->get_param('page');
        $per_page = $request->get_param('per_page');
        $orderby = $request->get_param('orderby');
        $order = $request->get_param('order');
        $type_filter = $request->get_param('type');
        $status = $request->get_param('status');
        $featured = $request->get_param('featured');
        $on_sale = $request->get_param('on_sale');
        $category = $request->get_param('category');
        
        // Build query arguments
        $args = array(
            'limit' => $per_page,
            'page' => $page,
            'paginate' => true,
            'orderby' => $orderby,
            'order' => $order,
            'status' => $status
        );
        
        // Filter by product type
        if ($type_filter === 'physical') {
            $args['type'] = 'simple';
        } elseif ($type_filter === 'variable') {
            $args['type'] = 'variable';
        } else {
            // Get both simple and variable products
            $args['type'] = array('simple', 'variable');
        }
        
        // Filter by featured
        if ($featured !== null) {
            $args['featured'] = $featured;
        }
        
        // Filter by on sale
        if ($on_sale !== null && $on_sale) {
            $args['on_sale'] = true;
        }
        
        // Filter by category
        if ($category) {
            $term = get_term_by('slug', $category, 'product_cat');
            if ($term) {
                $args['category'] = array($term->slug);
            }
        }
        
        // Get products
        $results = wc_get_products($args);
        
        // Format products
        $formatted_products = array();
        foreach ($results->products as $product) {
            // For physical type filter, exclude virtual products
            if ($type_filter === 'physical' && $product->is_virtual()) {
                continue;
            }
            
            $formatted_products[] = $this->format_product_response($product);
        }
        
        // Calculate pagination info
        $total_products = count($formatted_products);
        
        return rest_ensure_response(array(
            'success' => true,
            'total' => $total_products,
            'total_products' => $results->total,
            'total_pages' => $results->max_num_pages,
            'current_page' => $page,
            'per_page' => $per_page,
            'filters' => array(
                'type' => $type_filter,
                'status' => $status,
                'featured' => $featured,
                'on_sale' => $on_sale,
                'category' => $category
            ),
            'products' => $formatted_products
        ));
    }
    
    /**
     * Search all products with pagination
     */
    public function search_products($request) {
        $search_term = $request->get_param('s');
        $page = $request->get_param('page');
        $per_page = $request->get_param('per_page');
        $type_filter = $request->get_param('type');
        $orderby = $request->get_param('orderby');
        
        // Build query arguments
        $args = array(
            's' => $search_term,
            'limit' => $per_page,
            'page' => $page,
            'paginate' => true
        );
        
        // Filter by product type
        if ($type_filter === 'physical') {
            $args['type'] = 'simple';
        } elseif ($type_filter === 'variable') {
            $args['type'] = 'variable';
        } else {
            $args['type'] = array('simple', 'variable');
        }
        
        // Set ordering
        if ($orderby && $orderby !== 'relevance') {
            $args['orderby'] = $orderby;
            $args['order'] = 'DESC';
        }
        
        // Get products
        $results = wc_get_products($args);
        
        // Format products
        $formatted_products = array();
        foreach ($results->products as $product) {
            // For physical type filter, exclude virtual products
            if ($type_filter === 'physical' && $product->is_virtual()) {
                continue;
            }
            
            $formatted_products[] = $this->format_product_response($product);
        }
        
        return rest_ensure_response(array(
            'success' => true,
            'search_term' => $search_term,
            'total' => count($formatted_products),
            'total_results' => $results->total,
            'total_pages' => $results->max_num_pages,
            'current_page' => $page,
            'per_page' => $per_page,
            'type_filter' => $type_filter,
            'products' => $formatted_products
        ));
    }
    
    /**
     * Get single product (any type)
     */
    public function get_single_product($request) {
        $product_id = $request->get_param('id');
        $product = wc_get_product($product_id);
        
        if (!$product) {
            return new WP_Error(
                'product_not_found',
                __('Product not found.', 'woo-products-api'),
                array('status' => 404)
            );
        }
        
        return rest_ensure_response(array(
            'success' => true,
            'product' => $this->format_product_response($product)
        ));
    }
    
    /**
     * Get comprehensive statistics for all products
     */
    public function get_statistics($request) {
        // Get all simple products
        $simple_products = wc_get_products(array(
            'type' => 'simple',
            'limit' => -1,
            'status' => 'publish'
        ));
        
        // Get all variable products
        $variable_products = wc_get_products(array(
            'type' => 'variable',
            'limit' => -1,
            'status' => 'publish'
        ));
        
        // Calculate physical products (non-virtual simple products)
        $physical_products = array_filter($simple_products, function($product) {
            return !$product->is_virtual();
        });
        
        $total_simple = count($simple_products);
        $total_physical = count($physical_products);
        $total_variable = count($variable_products);
        $total_products = $total_simple + $total_variable;
        
        // Stock statistics
        $in_stock = 0;
        $out_of_stock = 0;
        $on_backorder = 0;
        $low_stock = 0;
        
        // Price statistics
        $total_value = 0;
        $on_sale_count = 0;
        $featured_count = 0;
        $total_sales = 0;
        
        // Process all products
        $all_products = array_merge($simple_products, $variable_products);
        
        foreach ($all_products as $product) {
            // Stock status
            if ($product->is_in_stock()) {
                $in_stock++;
            } else {
                $out_of_stock++;
            }
            
            if ($product->is_on_backorder()) {
                $on_backorder++;
            }
            
            // Check low stock
            if ($product->get_manage_stock() && $product->get_stock_quantity() <= $product->get_low_stock_amount()) {
                $low_stock++;
            }
            
            // Price and sales
            $price = floatval($product->get_price());
            $total_value += $price;
            
            if ($product->is_on_sale()) {
                $on_sale_count++;
            }
            
            if ($product->is_featured()) {
                $featured_count++;
            }
            
            $total_sales += intval($product->get_total_sales());
        }
        
        // Calculate variations count
        $total_variations = 0;
        foreach ($variable_products as $product) {
            $total_variations += count($product->get_children());
        }
        
        // Get categories count
        $categories = get_terms(array(
            'taxonomy' => 'product_cat',
            'hide_empty' => true
        ));
        
        return rest_ensure_response(array(
            'success' => true,
            'statistics' => array(
                'products_overview' => array(
                    'total_products' => $total_products,
                    'physical_products' => $total_physical,
                    'variable_products' => $total_variable,
                    'simple_products' => $total_simple,
                    'total_variations' => $total_variations
                ),
                'stock_status' => array(
                    'in_stock' => $in_stock,
                    'out_of_stock' => $out_of_stock,
                    'on_backorder' => $on_backorder,
                    'low_stock' => $low_stock
                ),
                'sales_info' => array(
                    'on_sale' => $on_sale_count,
                    'featured' => $featured_count,
                    'total_sales' => $total_sales,
                    'average_sales_per_product' => $total_products > 0 ? round($total_sales / $total_products, 2) : 0
                ),
                'pricing' => array(
                    'total_value' => wc_price($total_value),
                    'total_value_raw' => $total_value,
                    'average_price' => $total_products > 0 ? wc_price($total_value / $total_products) : wc_price(0),
                    'average_price_raw' => $total_products > 0 ? round($total_value / $total_products, 2) : 0
                ),
                'categories' => array(
                    'total_categories' => count($categories)
                )
            )
        ));
    }
    
    /**
     * Format product response with complete information
     */
    private function format_product_response($product) {
        $product_type = $product->get_type();
        
        // Base product data
        $response = array(
            'id' => $product->get_id(),
            'name' => $product->get_name(),
            'slug' => $product->get_slug(),
            'permalink' => $product->get_permalink(),
            'type' => $product_type,
            'status' => $product->get_status(),
            'featured' => $product->get_featured(),
            'catalog_visibility' => $product->get_catalog_visibility(),
            'description' => $product->get_description(),
            'short_description' => $product->get_short_description(),
            'sku' => $product->get_sku(),
            'price' => $product->get_price(),
            'price_html' => $product->get_price_html(),
            'regular_price' => $product->get_regular_price(),
            'sale_price' => $product->get_sale_price(),
            'on_sale' => $product->is_on_sale(),
            'stock_status' => $product->get_stock_status(),
            'stock_quantity' => $product->get_stock_quantity(),
            'manage_stock' => $product->get_manage_stock(),
            'backorders' => $product->get_backorders(),
            'low_stock_amount' => $product->get_low_stock_amount(),
            'sold_individually' => $product->get_sold_individually(),
            'reviews_allowed' => $product->get_reviews_allowed(),
            'average_rating' => $product->get_average_rating(),
            'rating_count' => $product->get_rating_count(),
            'total_sales' => $product->get_total_sales()
        );
        
        // Add physical product specific data
        if ($product_type === 'simple' && !$product->is_virtual()) {
            $response['is_physical'] = true;
            $response['weight'] = $product->get_weight();
            $response['dimensions'] = array(
                'length' => $product->get_length(),
                'width' => $product->get_width(),
                'height' => $product->get_height()
            );
            $response['shipping_class'] = $product->get_shipping_class();
        } else {
            $response['is_physical'] = false;
        }
        
        // Add variable product specific data
        if ($product_type === 'variable') {
            $variations_data = array();
            $variations = $product->get_children();
            
            foreach ($variations as $variation_id) {
                $variation = wc_get_product($variation_id);
                if ($variation) {
                    $variations_data[] = array(
                        'id' => $variation->get_id(),
                        'sku' => $variation->get_sku(),
                        'price' => $variation->get_price(),
                        'regular_price' => $variation->get_regular_price(),
                        'sale_price' => $variation->get_sale_price(),
                        'stock_status' => $variation->get_stock_status(),
                        'stock_quantity' => $variation->get_stock_quantity(),
                        'attributes' => $variation->get_attributes(),
                        'image' => array(
                            'id' => $variation->get_image_id(),
                            'src' => wp_get_attachment_image_url($variation->get_image_id(), 'full'),
                            'thumbnail' => wp_get_attachment_image_url($variation->get_image_id(), 'thumbnail')
                        )
                    );
                }
            }
            
            $attributes_data = array();
            $attributes = $product->get_attributes();
            
            foreach ($attributes as $attribute) {
                $attributes_data[] = array(
                    'name' => $attribute->get_name(),
                    'options' => $attribute->get_options(),
                    'visible' => $attribute->get_visible(),
                    'variation' => $attribute->get_variation()
                );
            }
            
            $response['attributes'] = $attributes_data;
            $response['variations'] = $variations_data;
            $response['variations_count'] = count($variations_data);
            $response['price_range'] = array(
                'min' => $product->get_variation_price('min'),
                'max' => $product->get_variation_price('max')
            );
        }
        
        // Get categories
        $categories = array();
        $category_ids = $product->get_category_ids();
        foreach ($category_ids as $cat_id) {
            $term = get_term($cat_id, 'product_cat');
            if ($term && !is_wp_error($term)) {
                $categories[] = array(
                    'id' => $term->term_id,
                    'name' => $term->name,
                    'slug' => $term->slug
                );
            }
        }
        $response['categories'] = $categories;
        
        // Get tags
        $tags = array();
        $tag_ids = $product->get_tag_ids();
        foreach ($tag_ids as $tag_id) {
            $term = get_term($tag_id, 'product_tag');
            if ($term && !is_wp_error($term)) {
                $tags[] = array(
                    'id' => $term->term_id,
                    'name' => $term->name,
                    'slug' => $term->slug
                );
            }
        }
        $response['tags'] = $tags;
        
        // Get gallery images
        $gallery_images = array();
        $gallery_ids = $product->get_gallery_image_ids();
        foreach ($gallery_ids as $img_id) {
            $gallery_images[] = array(
                'id' => $img_id,
                'src' => wp_get_attachment_image_url($img_id, 'full'),
                'thumbnail' => wp_get_attachment_image_url($img_id, 'thumbnail'),
                'alt' => get_post_meta($img_id, '_wp_attachment_image_alt', true)
            );
        }
        
        $response['images'] = array(
            'main_image' => array(
                'id' => $product->get_image_id(),
                'src' => wp_get_attachment_image_url($product->get_image_id(), 'full'),
                'thumbnail' => wp_get_attachment_image_url($product->get_image_id(), 'thumbnail'),
                'alt' => get_post_meta($product->get_image_id(), '_wp_attachment_image_alt', true)
            ),
            'gallery' => $gallery_images
        );
        
        // Dates
        $response['date_created'] = $product->get_date_created() ? $product->get_date_created()->date('Y-m-d H:i:s') : null;
        $response['date_modified'] = $product->get_date_modified() ? $product->get_date_modified()->date('Y-m-d H:i:s') : null;
        
        return $response;
    }
}

