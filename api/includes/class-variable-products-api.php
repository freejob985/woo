<?php
/**
 * Variable Products API Class
 * Handles all REST API endpoints for variable products with full field support and image upload
 */

if (!defined('ABSPATH')) {
    exit;
}

class WOO_Variable_Products_API {
    
    /**
     * Register REST API routes
     */
    public function register_routes() {
        // Create variable product
        register_rest_route(WOO_PRODUCTS_API_NAMESPACE, '/variable-products', array(
            'methods' => 'POST',
            'callback' => array($this, 'create_product'),
            'permission_callback' => array('WOO_Products_API_Authentication', 'check_permission')
        ));
        
        // Update variable product
        register_rest_route(WOO_PRODUCTS_API_NAMESPACE, '/variable-products/(?P<id>\d+)', array(
            'methods' => 'POST',
            'callback' => array($this, 'update_product'),
            'permission_callback' => array('WOO_Products_API_Authentication', 'check_permission')
        ));
        
        // Get all variable products with pagination
        register_rest_route(WOO_PRODUCTS_API_NAMESPACE, '/variable-products', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_all_products'),
            'permission_callback' => array('WOO_Products_API_Authentication', 'check_permission'),
            'args' => array(
                'page' => array(
                    'default' => 1,
                    'sanitize_callback' => 'absint'
                ),
                'per_page' => array(
                    'default' => 10,
                    'sanitize_callback' => 'absint'
                )
            )
        ));
        
        // Get single variable product
        register_rest_route(WOO_PRODUCTS_API_NAMESPACE, '/variable-products/(?P<id>\d+)', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_single_product'),
            'permission_callback' => array('WOO_Products_API_Authentication', 'check_permission')
        ));
        
        // Search variable products
        register_rest_route(WOO_PRODUCTS_API_NAMESPACE, '/variable-products/search', array(
            'methods' => 'GET',
            'callback' => array($this, 'search_products'),
            'permission_callback' => array('WOO_Products_API_Authentication', 'check_permission'),
            'args' => array(
                's' => array(
                    'required' => true,
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                'page' => array(
                    'default' => 1,
                    'sanitize_callback' => 'absint'
                ),
                'per_page' => array(
                    'default' => 10,
                    'sanitize_callback' => 'absint'
                )
            )
        ));
        
        // Delete variable product
        register_rest_route(WOO_PRODUCTS_API_NAMESPACE, '/variable-products/(?P<id>\d+)', array(
            'methods' => 'DELETE',
            'callback' => array($this, 'delete_product'),
            'permission_callback' => array('WOO_Products_API_Authentication', 'check_permission')
        ));
        
        // Get statistics
        register_rest_route(WOO_PRODUCTS_API_NAMESPACE, '/variable-products/stats', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_statistics'),
            'permission_callback' => array('WOO_Products_API_Authentication', 'check_permission')
        ));
    }
    
    /**
     * Create a new variable product
     */
    public function create_product($request) {
        try {
            $product = new WC_Product_Variable();
            
            // Set basic product data
            $this->set_product_data($product, $request);
            
            // Save product
            $product_id = $product->save();
            
            if (!$product_id) {
                return new WP_Error('product_creation_failed', __('Failed to create product.', 'woo-products-api'), array('status' => 500));
            }
            
            // Handle image uploads
            $this->handle_image_uploads($product_id, $request);
            
            // Create attributes from POST data
            $attributes_json = $request->get_param('attributes');
            if ($attributes_json) {
                $attributes_data = json_decode($attributes_json, true);
                if (is_array($attributes_data)) {
                    $this->create_attributes($product, $attributes_data);
                }
            }
            
            // Create variations from POST data
            $variations_json = $request->get_param('variations');
            if ($variations_json) {
                $variations_data = json_decode($variations_json, true);
                if (is_array($variations_data)) {
                    $this->create_variations($product_id, $variations_data, $request);
                }
            }
            
            // Sync product
            WC_Product_Variable::sync($product_id);
            
            // Add meta data for tracking
            update_post_meta($product_id, '_created_via_api', 'variable_products_api');
            update_post_meta($product_id, '_api_created_date', current_time('mysql'));
            
            // Reload product to get updated data
            $product = wc_get_product($product_id);
            
            return rest_ensure_response(array(
                'success' => true,
                'message' => __('Variable product created successfully.', 'woo-products-api'),
                'product_id' => $product_id,
                'product' => $this->format_product_response($product)
            ));
            
        } catch (Exception $e) {
            return new WP_Error('product_creation_error', $e->getMessage(), array('status' => 500));
        }
    }
    
    /**
     * Update an existing variable product
     */
    public function update_product($request) {
        $product_id = $request->get_param('id');
        
        $product = wc_get_product($product_id);
        
        if (!$product || $product->get_type() !== 'variable') {
            return new WP_Error('product_not_found', __('Variable product not found.', 'woo-products-api'), array('status' => 404));
        }
        
        try {
            $this->set_product_data($product, $request);
            $product->save();
            
            // Handle image uploads
            $this->handle_image_uploads($product_id, $request);
            
            // Update attributes if provided
            $attributes_json = $request->get_param('attributes');
            if ($attributes_json) {
                $attributes_data = json_decode($attributes_json, true);
                if (is_array($attributes_data)) {
                    $this->create_attributes($product, $attributes_data);
                }
            }
            
            // Sync product
            WC_Product_Variable::sync($product_id);
            
            // Update modified date
            update_post_meta($product_id, '_api_modified_date', current_time('mysql'));
            
            // Reload product
            $product = wc_get_product($product_id);
            
            return rest_ensure_response(array(
                'success' => true,
                'message' => __('Variable product updated successfully.', 'woo-products-api'),
                'product' => $this->format_product_response($product)
            ));
            
        } catch (Exception $e) {
            return new WP_Error('product_update_error', $e->getMessage(), array('status' => 500));
        }
    }
    
    /**
     * Get all variable products with pagination
     */
    public function get_all_products($request) {
        $page = $request->get_param('page');
        $per_page = $request->get_param('per_page');
        
        $args = array(
            'type' => 'variable',
            'limit' => $per_page,
            'page' => $page,
            'paginate' => true
        );
        
        $results = wc_get_products($args);
        
        $variable_products = array();
        foreach ($results->products as $product) {
            $variable_products[] = $this->format_product_response($product);
        }
        
        return rest_ensure_response(array(
            'success' => true,
            'total' => $results->total,
            'total_pages' => $results->max_num_pages,
            'current_page' => $page,
            'per_page' => $per_page,
            'products' => $variable_products
        ));
    }
    
    /**
     * Get single variable product
     */
    public function get_single_product($request) {
        $product_id = $request->get_param('id');
        $product = wc_get_product($product_id);
        
        if (!$product || $product->get_type() !== 'variable') {
            return new WP_Error('product_not_found', __('Variable product not found.', 'woo-products-api'), array('status' => 404));
        }
        
        return rest_ensure_response(array(
            'success' => true,
            'product' => $this->format_product_response($product)
        ));
    }
    
    /**
     * Search variable products
     */
    public function search_products($request) {
        $search_term = $request->get_param('s');
        $page = $request->get_param('page');
        $per_page = $request->get_param('per_page');
        
        $args = array(
            'type' => 'variable',
            's' => $search_term,
            'limit' => $per_page,
            'page' => $page,
            'paginate' => true
        );
        
        $results = wc_get_products($args);
        
        $variable_products = array();
        foreach ($results->products as $product) {
            $variable_products[] = $this->format_product_response($product);
        }
        
        return rest_ensure_response(array(
            'success' => true,
            'search_term' => $search_term,
            'total' => $results->total,
            'total_pages' => $results->max_num_pages,
            'current_page' => $page,
            'products' => $variable_products
        ));
    }
    
    /**
     * Delete variable product
     */
    public function delete_product($request) {
        $product_id = $request->get_param('id');
        $product = wc_get_product($product_id);
        
        if (!$product || $product->get_type() !== 'variable') {
            return new WP_Error('product_not_found', __('Variable product not found.', 'woo-products-api'), array('status' => 404));
        }
        
        $product_data = $this->format_product_response($product);
        
        // Delete all variations first
        $variations = $product->get_children();
        foreach ($variations as $variation_id) {
            wp_delete_post($variation_id, true);
        }
        
        // Force delete product
        $deleted = $product->delete(true);
        
        if (!$deleted) {
            return new WP_Error('product_deletion_failed', __('Failed to delete product.', 'woo-products-api'), array('status' => 500));
        }
        
        return rest_ensure_response(array(
            'success' => true,
            'message' => __('Variable product deleted successfully.', 'woo-products-api'),
            'deleted_product' => $product_data
        ));
    }
    
    /**
     * Get statistics for variable products
     */
    public function get_statistics($request) {
        $args = array(
            'type' => 'variable',
            'limit' => -1
        );
        
        $products = wc_get_products($args);
        
        $total_count = count($products);
        $total_variations = 0;
        $in_stock = 0;
        $out_of_stock = 0;
        $price_range = array('min' => PHP_INT_MAX, 'max' => 0);
        
        foreach ($products as $product) {
            $variations = $product->get_children();
            $total_variations += count($variations);
            
            if ($product->is_in_stock()) {
                $in_stock++;
            } else {
                $out_of_stock++;
            }
            
            $min_price = floatval($product->get_variation_price('min'));
            $max_price = floatval($product->get_variation_price('max'));
            
            if ($min_price < $price_range['min'] && $min_price > 0) {
                $price_range['min'] = $min_price;
            }
            if ($max_price > $price_range['max']) {
                $price_range['max'] = $max_price;
            }
        }
        
        return rest_ensure_response(array(
            'success' => true,
            'statistics' => array(
                'total_products' => $total_count,
                'total_variations' => $total_variations,
                'average_variations_per_product' => $total_count > 0 ? round($total_variations / $total_count, 2) : 0,
                'in_stock' => $in_stock,
                'out_of_stock' => $out_of_stock,
                'price_range' => array(
                    'min' => wc_price($price_range['min'] == PHP_INT_MAX ? 0 : $price_range['min']),
                    'max' => wc_price($price_range['max']),
                    'min_raw' => $price_range['min'] == PHP_INT_MAX ? 0 : $price_range['min'],
                    'max_raw' => $price_range['max']
                )
            )
        ));
    }
    
    /**
     * Set product data from request
     */
    private function set_product_data($product, $request) {
        if ($request->get_param('name')) {
            $product->set_name(sanitize_text_field($request->get_param('name')));
        }
        
        if ($request->get_param('description')) {
            $product->set_description(wp_kses_post($request->get_param('description')));
        }
        
        if ($request->get_param('short_description')) {
            $product->set_short_description(wp_kses_post($request->get_param('short_description')));
        }
        
        if ($request->get_param('sku')) {
            $product->set_sku(sanitize_text_field($request->get_param('sku')));
        }
        
        if ($request->get_param('status')) {
            $product->set_status(sanitize_text_field($request->get_param('status')));
        }
        
        if ($request->get_param('featured') !== null) {
            $product->set_featured(filter_var($request->get_param('featured'), FILTER_VALIDATE_BOOLEAN));
        }
    }
    
    /**
     * Handle image uploads
     */
    private function handle_image_uploads($product_id, $request) {
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');
        
        $files = $request->get_file_params();
        
        // Handle main image
        if (isset($files['main_image']) && !empty($files['main_image']['tmp_name'])) {
            $attachment_id = $this->upload_image($files['main_image'], $product_id);
            if ($attachment_id && !is_wp_error($attachment_id)) {
                set_post_thumbnail($product_id, $attachment_id);
            }
        }
        
        // Handle gallery images
        if (isset($files['gallery_images'])) {
            $gallery_ids = array();
            
            if (is_array($files['gallery_images']['tmp_name'])) {
                $file_count = count($files['gallery_images']['tmp_name']);
                for ($i = 0; $i < $file_count; $i++) {
                    if (!empty($files['gallery_images']['tmp_name'][$i])) {
                        $file = array(
                            'name' => $files['gallery_images']['name'][$i],
                            'type' => $files['gallery_images']['type'][$i],
                            'tmp_name' => $files['gallery_images']['tmp_name'][$i],
                            'error' => $files['gallery_images']['error'][$i],
                            'size' => $files['gallery_images']['size'][$i]
                        );
                        
                        $attachment_id = $this->upload_image($file, $product_id);
                        if ($attachment_id && !is_wp_error($attachment_id)) {
                            $gallery_ids[] = $attachment_id;
                        }
                    }
                }
            }
            
            if (!empty($gallery_ids)) {
                update_post_meta($product_id, '_product_image_gallery', implode(',', $gallery_ids));
            }
        }
    }
    
    /**
     * Upload single image
     */
    private function upload_image($file, $product_id) {
        $upload = wp_handle_upload($file, array('test_form' => false));
        
        if (isset($upload['error'])) {
            return new WP_Error('upload_error', $upload['error']);
        }
        
        $file_path = $upload['file'];
        $file_name = basename($file_path);
        $file_type = wp_check_filetype($file_name, null);
        
        $attachment = array(
            'post_mime_type' => $file_type['type'],
            'post_title' => sanitize_file_name($file_name),
            'post_content' => '',
            'post_status' => 'inherit'
        );
        
        $attachment_id = wp_insert_attachment($attachment, $file_path, $product_id);
        
        if (!is_wp_error($attachment_id)) {
            $attachment_data = wp_generate_attachment_metadata($attachment_id, $file_path);
            wp_update_attachment_metadata($attachment_id, $attachment_data);
        }
        
        return $attachment_id;
    }
    
    /**
     * Create attributes for variable product
     */
    private function create_attributes($product, $attributes_data) {
        $attributes = array();
        
        foreach ($attributes_data as $index => $attribute_data) {
            $attribute = new WC_Product_Attribute();
            $attribute->set_name($attribute_data['name']);
            $attribute->set_options($attribute_data['options']);
            $attribute->set_position($index);
            $attribute->set_visible(true);
            $attribute->set_variation(true);
            
            $attributes[] = $attribute;
        }
        
        $product->set_attributes($attributes);
        $product->save();
    }
    
    /**
     * Create variations for variable product
     */
    private function create_variations($product_id, $variations_data, $request) {
        $files = $request->get_file_params();
        
        foreach ($variations_data as $index => $variation_data) {
            $variation = new WC_Product_Variation();
            $variation->set_parent_id($product_id);
            
            if (isset($variation_data['attributes'])) {
                $variation->set_attributes($variation_data['attributes']);
            }
            
            if (isset($variation_data['regular_price'])) {
                $variation->set_regular_price($variation_data['regular_price']);
            }
            
            if (isset($variation_data['sale_price'])) {
                $variation->set_sale_price($variation_data['sale_price']);
            }
            
            if (isset($variation_data['stock_quantity'])) {
                $variation->set_manage_stock(true);
                $variation->set_stock_quantity($variation_data['stock_quantity']);
            }
            
            if (isset($variation_data['stock_status'])) {
                $variation->set_stock_status($variation_data['stock_status']);
            }
            
            if (isset($variation_data['sku'])) {
                $variation->set_sku($variation_data['sku']);
            }
            
            $variation_id = $variation->save();
            
            // Handle variation image if provided
            if (isset($files["variation_image_{$index}"]) && !empty($files["variation_image_{$index}"]['tmp_name'])) {
                $attachment_id = $this->upload_image($files["variation_image_{$index}"], $variation_id);
                if ($attachment_id && !is_wp_error($attachment_id)) {
                    set_post_thumbnail($variation_id, $attachment_id);
                }
            }
        }
    }
    
    /**
     * Format product response
     */
    private function format_product_response($product) {
        $variations_data = array();
        
        if ($product->get_type() === 'variable') {
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
                        'image' => wp_get_attachment_image_url($variation->get_image_id(), 'full')
                    );
                }
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
        
        // Get gallery images
        $gallery_images = array();
        $gallery_ids = $product->get_gallery_image_ids();
        foreach ($gallery_ids as $img_id) {
            $gallery_images[] = array(
                'id' => $img_id,
                'src' => wp_get_attachment_image_url($img_id, 'full'),
                'thumbnail' => wp_get_attachment_image_url($img_id, 'thumbnail')
            );
        }
        
        return array(
            'id' => $product->get_id(),
            'name' => $product->get_name(),
            'slug' => $product->get_slug(),
            'type' => $product->get_type(),
            'status' => $product->get_status(),
            'featured' => $product->get_featured(),
            'description' => $product->get_description(),
            'short_description' => $product->get_short_description(),
            'sku' => $product->get_sku(),
            'price' => $product->get_price(),
            'price_html' => $product->get_price_html(),
            'attributes' => $attributes_data,
            'variations' => $variations_data,
            'variations_count' => count($variations_data),
            'images' => array(
                'main_image' => array(
                    'id' => $product->get_image_id(),
                    'src' => wp_get_attachment_image_url($product->get_image_id(), 'full'),
                    'thumbnail' => wp_get_attachment_image_url($product->get_image_id(), 'thumbnail')
                ),
                'gallery' => $gallery_images
            ),
            'permalink' => $product->get_permalink()
        );
    }
}
