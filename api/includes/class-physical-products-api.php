<?php
/**
 * Physical Products API Class
 * Handles all REST API endpoints for physical products with full field support and image upload
 */

if (!defined('ABSPATH')) {
    exit;
}

class WOO_Physical_Products_API {
    
    /**
     * Register REST API routes
     */
    public function register_routes() {
        // Create physical product - استخدام POST  لدعم رفع الصور
        register_rest_route(WOO_PRODUCTS_API_NAMESPACE, '/physical-products', array(
            'methods' => 'POST',
            'callback' => array($this, 'create_product'),
            'permission_callback' => array('WOO_Products_API_Authentication', 'check_permission')
        ));
        
        // Update physical product - استخدام POST مع _method=PUT
        register_rest_route(WOO_PRODUCTS_API_NAMESPACE, '/physical-products/(?P<id>\d+)', array(
            'methods' => 'POST',
            'callback' => array($this, 'update_product'),
            'permission_callback' => array('WOO_Products_API_Authentication', 'check_permission')
        ));
        
        // Get all physical products with pagination
        register_rest_route(WOO_PRODUCTS_API_NAMESPACE, '/physical-products', array(
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
                )
            )
        ));
        
        // Get single physical product
        register_rest_route(WOO_PRODUCTS_API_NAMESPACE, '/physical-products/(?P<id>\d+)', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_single_product'),
            'permission_callback' => array('WOO_Products_API_Authentication', 'check_permission')
        ));
        
        // Search physical products
        register_rest_route(WOO_PRODUCTS_API_NAMESPACE, '/physical-products/search', array(
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
                )
            )
        ));
        
        // Delete physical product
        register_rest_route(WOO_PRODUCTS_API_NAMESPACE, '/physical-products/(?P<id>\d+)', array(
            'methods' => 'DELETE',
            'callback' => array($this, 'delete_product'),
            'permission_callback' => array('WOO_Products_API_Authentication', 'check_permission')
        ));
        
        // Get statistics
        register_rest_route(WOO_PRODUCTS_API_NAMESPACE, '/physical-products/stats', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_statistics'),
            'permission_callback' => array('WOO_Products_API_Authentication', 'check_permission')
        ));
    }
    
    /**
     * Create a new physical product
     * يدعم جميع حقول المنتج + رفع الصور
     */
    public function create_product($request) {
        try {
            $product = new WC_Product_Simple();
            
            // Set product data from form parameters
            $this->set_product_data($product, $request);
            
            // Set as physical product
            $product->set_virtual(false);
            $product->set_downloadable(false);
            
            // Save product
            $product_id = $product->save();
            
            if (!$product_id) {
                return new WP_Error(
                    'product_creation_failed',
                    __('Failed to create product.', 'woo-products-api'),
                    array('status' => 500)
                );
            }
            
            // Handle image uploads
            $this->handle_image_uploads($product_id, $request);
            
            // Handle categories
            $this->handle_categories($product_id, $request);
            
            // Handle tags
            $this->handle_tags($product_id, $request);
            
            // Add meta data for tracking
            update_post_meta($product_id, '_created_via_api', 'physical_products_api');
            update_post_meta($product_id, '_api_created_date', current_time('mysql'));
            
            // Reload product to get updated data
            $product = wc_get_product($product_id);
            
            return rest_ensure_response(array(
                'success' => true,
                'message' => __('Product created successfully.', 'woo-products-api'),
                'product_id' => $product_id,
                'product' => $this->format_product_response($product)
            ));
            
        } catch (Exception $e) {
            return new WP_Error(
                'product_creation_error',
                $e->getMessage(),
                array('status' => 500)
            );
        }
    }
    
    /**
     * Update an existing physical product
     */
    public function update_product($request) {
        $product_id = $request->get_param('id');
        
        $product = wc_get_product($product_id);
        
        if (!$product || $product->get_type() !== 'simple') {
            return new WP_Error(
                'product_not_found',
                __('Physical product not found.', 'woo-products-api'),
                array('status' => 404)
            );
        }
        
        try {
            $this->set_product_data($product, $request);
            $product->save();
            
            // Handle image uploads
            $this->handle_image_uploads($product_id, $request);
            
            // Handle categories
            $this->handle_categories($product_id, $request);
            
            // Handle tags
            $this->handle_tags($product_id, $request);
            
            // Update modified date
            update_post_meta($product_id, '_api_modified_date', current_time('mysql'));
            
            // Reload product
            $product = wc_get_product($product_id);
            
            return rest_ensure_response(array(
                'success' => true,
                'message' => __('Product updated successfully.', 'woo-products-api'),
                'product' => $this->format_product_response($product)
            ));
            
        } catch (Exception $e) {
            return new WP_Error(
                'product_update_error',
                $e->getMessage(),
                array('status' => 500)
            );
        }
    }
    
    /**
     * Get all physical products with pagination
     */
    public function get_all_products($request) {
        $page = $request->get_param('page');
        $per_page = $request->get_param('per_page');
        $orderby = $request->get_param('orderby');
        $order = $request->get_param('order');
        
        $args = array(
            'type' => 'simple',
            'limit' => $per_page,
            'page' => $page,
            'paginate' => true,
            'orderby' => $orderby,
            'order' => $order
        );
        
        $results = wc_get_products($args);
        
        // Filter only physical products (not virtual)
        $physical_products = array();
        foreach ($results->products as $product) {
            if (!$product->is_virtual()) {
                $physical_products[] = $this->format_product_response($product);
            }
        }
        
        return rest_ensure_response(array(
            'success' => true,
            'total' => count($physical_products),
            'total_products' => $results->total,
            'total_pages' => $results->max_num_pages,
            'current_page' => $page,
            'per_page' => $per_page,
            'products' => $physical_products
        ));
    }
    
    /**
     * Get single physical product
     */
    public function get_single_product($request) {
        $product_id = $request->get_param('id');
        $product = wc_get_product($product_id);
        
        if (!$product || $product->get_type() !== 'simple' || $product->is_virtual()) {
            return new WP_Error(
                'product_not_found',
                __('Physical product not found.', 'woo-products-api'),
                array('status' => 404)
            );
        }
        
        return rest_ensure_response(array(
            'success' => true,
            'product' => $this->format_product_response($product)
        ));
    }
    
    /**
     * Search physical products
     */
    public function search_products($request) {
        $search_term = $request->get_param('s');
        $page = $request->get_param('page');
        $per_page = $request->get_param('per_page');
        
        $args = array(
            'type' => 'simple',
            's' => $search_term,
            'limit' => $per_page,
            'page' => $page,
            'paginate' => true
        );
        
        $results = wc_get_products($args);
        
        // Filter only physical products
        $physical_products = array();
        foreach ($results->products as $product) {
            if (!$product->is_virtual()) {
                $physical_products[] = $this->format_product_response($product);
            }
        }
        
        return rest_ensure_response(array(
            'success' => true,
            'search_term' => $search_term,
            'total' => count($physical_products),
            'total_pages' => $results->max_num_pages,
            'current_page' => $page,
            'products' => $physical_products
        ));
    }
    
    /**
     * Delete physical product
     */
    public function delete_product($request) {
        $product_id = $request->get_param('id');
        $product = wc_get_product($product_id);
        
        if (!$product || $product->get_type() !== 'simple') {
            return new WP_Error(
                'product_not_found',
                __('Physical product not found.', 'woo-products-api'),
                array('status' => 404)
            );
        }
        
        $product_data = $this->format_product_response($product);
        
        // Force delete
        $deleted = $product->delete(true);
        
        if (!$deleted) {
            return new WP_Error(
                'product_deletion_failed',
                __('Failed to delete product.', 'woo-products-api'),
                array('status' => 500)
            );
        }
        
        return rest_ensure_response(array(
            'success' => true,
            'message' => __('Product deleted successfully.', 'woo-products-api'),
            'deleted_product' => $product_data
        ));
    }
    
    /**
     * Get statistics for physical products
     */
    public function get_statistics($request) {
        $args = array(
            'type' => 'simple',
            'limit' => -1
        );
        
        $products = wc_get_products($args);
        
        // Filter physical products
        $physical_products = array_filter($products, function($product) {
            return !$product->is_virtual();
        });
        
        $total_count = count($physical_products);
        $in_stock = 0;
        $out_of_stock = 0;
        $on_backorder = 0;
        $total_value = 0;
        $total_stock_quantity = 0;
        $total_sales = 0;
        
        foreach ($physical_products as $product) {
            if ($product->is_in_stock()) {
                $in_stock++;
            } else {
                $out_of_stock++;
            }
            
            if ($product->is_on_backorder()) {
                $on_backorder++;
            }
            
            $total_value += floatval($product->get_price());
            $total_stock_quantity += intval($product->get_stock_quantity());
            $total_sales += intval($product->get_total_sales());
        }
        
        return rest_ensure_response(array(
            'success' => true,
            'statistics' => array(
                'total_products' => $total_count,
                'in_stock' => $in_stock,
                'out_of_stock' => $out_of_stock,
                'on_backorder' => $on_backorder,
                'total_value' => wc_price($total_value),
                'total_value_raw' => $total_value,
                'total_stock_quantity' => $total_stock_quantity,
                'total_sales' => $total_sales,
                'average_price' => $total_count > 0 ? wc_price($total_value / $total_count) : 0,
                'average_price_raw' => $total_count > 0 ? $total_value / $total_count : 0
            )
        ));
    }
    
    /**
     * Set product data from request - جميع الحقول المتاحة
     */
    private function set_product_data($product, $request) {
        // === معلومات أساسية ===
        
        // اسم المنتج (مطلوب)
        if ($request->get_param('name')) {
            $product->set_name(sanitize_text_field($request->get_param('name')));
        }
        
        // الوصف الكامل
        if ($request->get_param('description')) {
            $product->set_description(wp_kses_post($request->get_param('description')));
        }
        
        // الوصف المختصر
        if ($request->get_param('short_description')) {
            $product->set_short_description(wp_kses_post($request->get_param('short_description')));
        }
        
        // SKU - رقم تعريفي فريد
        if ($request->get_param('sku')) {
            $product->set_sku(sanitize_text_field($request->get_param('sku')));
        }
        
        // === الأسعار ===
        
        // السعر العادي
        if ($request->get_param('regular_price')) {
            $product->set_regular_price($request->get_param('regular_price'));
        }
        
        // سعر التخفيض
        if ($request->get_param('sale_price')) {
            $product->set_sale_price($request->get_param('sale_price'));
        }
        
        // تاريخ بداية التخفيض
        if ($request->get_param('date_on_sale_from')) {
            $product->set_date_on_sale_from($request->get_param('date_on_sale_from'));
        }
        
        // تاريخ نهاية التخفيض
        if ($request->get_param('date_on_sale_to')) {
            $product->set_date_on_sale_to($request->get_param('date_on_sale_to'));
        }
        
        // === المخزون ===
        
        // إدارة المخزون
        if ($request->get_param('manage_stock') !== null) {
            $manage_stock = filter_var($request->get_param('manage_stock'), FILTER_VALIDATE_BOOLEAN);
            $product->set_manage_stock($manage_stock);
            
            // كمية المخزون (إذا كانت إدارة المخزون مفعلة)
            if ($manage_stock && $request->get_param('stock_quantity') !== null) {
                $product->set_stock_quantity($request->get_param('stock_quantity'));
            }
        }
        
        // حالة المخزون: instock, outofstock, onbackorder
        if ($request->get_param('stock_status')) {
            $product->set_stock_status(sanitize_text_field($request->get_param('stock_status')));
        }
        
        // السماح بالطلب المسبق (backorders): no, notify, yes
        if ($request->get_param('backorders')) {
            $product->set_backorders(sanitize_text_field($request->get_param('backorders')));
        }
        
        // البيع الفردي (سماح بشراء قطعة واحدة فقط)
        if ($request->get_param('sold_individually') !== null) {
            $product->set_sold_individually(filter_var($request->get_param('sold_individually'), FILTER_VALIDATE_BOOLEAN));
        }
        
        // === الشحن (للمنتجات الفيزيائية) ===
        
        // الوزن (بالكيلوجرام)
        if ($request->get_param('weight')) {
            $product->set_weight($request->get_param('weight'));
        }
        
        // الطول (بالسنتيمتر)
        if ($request->get_param('length')) {
            $product->set_length($request->get_param('length'));
        }
        
        // العرض (بالسنتيمتر)
        if ($request->get_param('width')) {
            $product->set_width($request->get_param('width'));
        }
        
        // الارتفاع (بالسنتيمتر)
        if ($request->get_param('height')) {
            $product->set_height($request->get_param('height'));
        }
        
        // فئة الشحن
        if ($request->get_param('shipping_class')) {
            $shipping_class = sanitize_text_field($request->get_param('shipping_class'));
            $shipping_class_id = get_term_by('slug', $shipping_class, 'product_shipping_class');
            if ($shipping_class_id) {
                $product->set_shipping_class_id($shipping_class_id->term_id);
            }
        }
        
        // === الضرائب ===
        
        // حالة الضريبة: taxable, shipping, none
        if ($request->get_param('tax_status')) {
            $product->set_tax_status(sanitize_text_field($request->get_param('tax_status')));
        }
        
        // فئة الضريبة
        if ($request->get_param('tax_class')) {
            $product->set_tax_class(sanitize_text_field($request->get_param('tax_class')));
        }
        
        // === إعدادات إضافية ===
        
        // حالة المنتج: publish, draft, pending
        if ($request->get_param('status')) {
            $product->set_status(sanitize_text_field($request->get_param('status')));
        }
        
        // المنتج مميز (featured)
        if ($request->get_param('featured') !== null) {
            $product->set_featured(filter_var($request->get_param('featured'), FILTER_VALIDATE_BOOLEAN));
        }
        
        // ظهور في الكتالوج: visible, catalog, search, hidden
        if ($request->get_param('catalog_visibility')) {
            $product->set_catalog_visibility(sanitize_text_field($request->get_param('catalog_visibility')));
        }
        
        // تقييم المراجعات
        if ($request->get_param('reviews_allowed') !== null) {
            $product->set_reviews_allowed(filter_var($request->get_param('reviews_allowed'), FILTER_VALIDATE_BOOLEAN));
        }
        
        // ملاحظة الشراء
        if ($request->get_param('purchase_note')) {
            $product->set_purchase_note(wp_kses_post($request->get_param('purchase_note')));
        }
        
        // رابط خارجي للمنتج
        if ($request->get_param('external_url')) {
            $product->set_button_text(sanitize_text_field($request->get_param('button_text') ?: 'Buy Now'));
            update_post_meta($product->get_id(), '_product_url', esc_url_raw($request->get_param('external_url')));
        }
        
        // قائمة الانتظار
        if ($request->get_param('low_stock_amount')) {
            $product->set_low_stock_amount($request->get_param('low_stock_amount'));
        }
        
        // === ميتا بيانات مخصصة ===
        if ($request->get_param('meta_data')) {
            $meta_data = $request->get_param('meta_data');
            if (is_array($meta_data)) {
                foreach ($meta_data as $key => $value) {
                    $product->update_meta_data(sanitize_key($key), sanitize_text_field($value));
                }
            }
        }
    }
    
    /**
     * Handle image uploads - رفع الصورة الرئيسية وصور المعرض
     */
    private function handle_image_uploads($product_id, $request) {
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');
        
        $files = $request->get_file_params();
        
        // Handle main image - الصورة الرئيسية
        if (isset($files['main_image']) && !empty($files['main_image']['tmp_name'])) {
            $attachment_id = $this->upload_image($files['main_image'], $product_id);
            if ($attachment_id && !is_wp_error($attachment_id)) {
                set_post_thumbnail($product_id, $attachment_id);
            }
        }
        
        // Handle gallery images - صور المعرض
        if (isset($files['gallery_images'])) {
            $gallery_ids = array();
            
            // Support multiple files
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
            } else {
                // Single file
                if (!empty($files['gallery_images']['tmp_name'])) {
                    $attachment_id = $this->upload_image($files['gallery_images'], $product_id);
                    if ($attachment_id && !is_wp_error($attachment_id)) {
                        $gallery_ids[] = $attachment_id;
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
     * Handle categories
     */
    private function handle_categories($product_id, $request) {
        $categories = $request->get_param('categories');
        if ($categories) {
            $category_ids = array();
            
            if (is_string($categories)) {
                $categories = explode(',', $categories);
            }
            
            foreach ($categories as $category) {
                $term = get_term_by('slug', trim($category), 'product_cat');
                if (!$term) {
                    $term = get_term_by('name', trim($category), 'product_cat');
                }
                if (!$term) {
                    // Create new category
                    $new_term = wp_insert_term(trim($category), 'product_cat');
                    if (!is_wp_error($new_term)) {
                        $category_ids[] = $new_term['term_id'];
                    }
                } else {
                    $category_ids[] = $term->term_id;
                }
            }
            
            if (!empty($category_ids)) {
                wp_set_object_terms($product_id, $category_ids, 'product_cat');
            }
        }
    }
    
    /**
     * Handle tags
     */
    private function handle_tags($product_id, $request) {
        $tags = $request->get_param('tags');
        if ($tags) {
            if (is_string($tags)) {
                $tags = explode(',', $tags);
            }
            
            $tag_ids = array();
            foreach ($tags as $tag) {
                $term = get_term_by('slug', trim($tag), 'product_tag');
                if (!$term) {
                    $term = get_term_by('name', trim($tag), 'product_tag');
                }
                if (!$term) {
                    $new_term = wp_insert_term(trim($tag), 'product_tag');
                    if (!is_wp_error($new_term)) {
                        $tag_ids[] = $new_term['term_id'];
                    }
                } else {
                    $tag_ids[] = $term->term_id;
                }
            }
            
            if (!empty($tag_ids)) {
                wp_set_object_terms($product_id, $tag_ids, 'product_tag');
            }
        }
    }
    
    /**
     * Format product response - جميع المعلومات
     */
    private function format_product_response($product) {
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
        
        return array(
            'id' => $product->get_id(),
            'name' => $product->get_name(),
            'slug' => $product->get_slug(),
            'permalink' => $product->get_permalink(),
            'type' => $product->get_type(),
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
            'date_on_sale_from' => $product->get_date_on_sale_from() ? $product->get_date_on_sale_from()->date('Y-m-d H:i:s') : null,
            'date_on_sale_to' => $product->get_date_on_sale_to() ? $product->get_date_on_sale_to()->date('Y-m-d H:i:s') : null,
            'stock_status' => $product->get_stock_status(),
            'stock_quantity' => $product->get_stock_quantity(),
            'manage_stock' => $product->get_manage_stock(),
            'backorders' => $product->get_backorders(),
            'backorders_allowed' => $product->backorders_allowed(),
            'backordered' => $product->is_on_backorder(),
            'sold_individually' => $product->get_sold_individually(),
            'weight' => $product->get_weight(),
            'dimensions' => array(
                'length' => $product->get_length(),
                'width' => $product->get_width(),
                'height' => $product->get_height()
            ),
            'shipping_class' => $product->get_shipping_class(),
            'tax_status' => $product->get_tax_status(),
            'tax_class' => $product->get_tax_class(),
            'reviews_allowed' => $product->get_reviews_allowed(),
            'average_rating' => $product->get_average_rating(),
            'rating_count' => $product->get_rating_count(),
            'total_sales' => $product->get_total_sales(),
            'purchase_note' => $product->get_purchase_note(),
            'categories' => $categories,
            'tags' => $tags,
            'images' => array(
                'main_image' => array(
                    'id' => $product->get_image_id(),
                    'src' => wp_get_attachment_image_url($product->get_image_id(), 'full'),
                    'thumbnail' => wp_get_attachment_image_url($product->get_image_id(), 'thumbnail'),
                    'alt' => get_post_meta($product->get_image_id(), '_wp_attachment_image_alt', true)
                ),
                'gallery' => $gallery_images
            ),
            'date_created' => $product->get_date_created() ? $product->get_date_created()->date('Y-m-d H:i:s') : null,
            'date_modified' => $product->get_date_modified() ? $product->get_date_modified()->date('Y-m-d H:i:s') : null
        );
    }
}

