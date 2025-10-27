<?php
/**
 * Plugin Name: WooCommerce Products API Manager
 * Plugin URI: https://dev.murjan.sa
 * Description: REST API لإدارة المنتجات الفيزيائية والمتغيرة في WooCommerce مع دعم الصور والحقول الكاملة
 * Version: 1.0.0
 * Author: Murjan Team
 * Author URI: https://dev.murjan.sa
 * Text Domain: woo-products-api
 * Domain Path: /languages
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * WC requires at least: 5.0
 * WC tested up to: 8.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('WOO_PRODUCTS_API_VERSION', '1.0.0');
define('WOO_PRODUCTS_API_DIR', plugin_dir_path(__FILE__));
define('WOO_PRODUCTS_API_URL', plugin_dir_url(__FILE__));
define('WOO_PRODUCTS_API_NAMESPACE', 'murjan-api/v1');

// Check if WooCommerce is active
if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    add_action('admin_notices', 'woo_products_api_woocommerce_missing_notice');
    return;
}

function woo_products_api_woocommerce_missing_notice() {
    ?>
    <div class="error">
        <p><?php _e('WooCommerce Products API Manager requires WooCommerce to be installed and active.', 'woo-products-api'); ?></p>
    </div>
    <?php
}

// Include required files
require_once WOO_PRODUCTS_API_DIR . 'includes/class-authentication.php';
require_once WOO_PRODUCTS_API_DIR . 'includes/class-physical-products-api.php';
require_once WOO_PRODUCTS_API_DIR . 'includes/class-variable-products-api.php';
require_once WOO_PRODUCTS_API_DIR . 'includes/class-all-products-api.php';


// Add CORS headers for external access
add_action('init', function() {
    // Handle preflight requests early
    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, PATCH');
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Allow-Headers: Authorization, Content-Type, X-WP-Nonce, X-Requested-With, Accept, Origin');
        header('Access-Control-Max-Age: 86400');
        status_header(200);
        exit();
    }
});

add_action('rest_api_init', function() {
    remove_filter('rest_pre_serve_request', 'rest_send_cors_headers');
    add_filter('rest_pre_serve_request', function($value) {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, PATCH');
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Allow-Headers: Authorization, Content-Type, X-WP-Nonce, X-Requested-With, Accept, Origin');
        header('Access-Control-Expose-Headers: X-WP-Total, X-WP-TotalPages');
        
        return $value;
    });
}, 15);

// Additional CORS header for all requests
add_action('send_headers', function() {
    if (!headers_sent()) {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, PATCH');
        header('Access-Control-Allow-Headers: Authorization, Content-Type, X-WP-Nonce, X-Requested-With, Accept, Origin');
    }
});

// Initialize the plugin
add_action('rest_api_init', 'woo_products_api_register_routes');

function woo_products_api_register_routes() {
    // Initialize Physical Products API
    $physical_products_api = new WOO_Physical_Products_API();
    $physical_products_api->register_routes();
    
    // Initialize Variable Products API
    $variable_products_api = new WOO_Variable_Products_API();
    $variable_products_api->register_routes();
    
    // Initialize All Products API
    $all_products_api = new WOO_All_Products_API();
    $all_products_api->register_routes();
}

// Add admin menu for API settings
add_action('admin_menu', 'woo_products_api_add_admin_menu');

function woo_products_api_add_admin_menu() {
    add_menu_page(
        __('Products API Manager', 'woo-products-api'),
        __('Products API', 'woo-products-api'),
        'manage_woocommerce',
        'woo-products-api',
        'woo_products_api_admin_page',
        'dashicons-rest-api',
        57
    );
}

// Admin page content
function woo_products_api_admin_page() {
    $base_url = rest_url(WOO_PRODUCTS_API_NAMESPACE);
    ?>
    <div class="wrap">
        <h1><?php _e('WooCommerce Products API Manager', 'woo-products-api'); ?></h1>
        
        <div class="card" style="max-width: 1200px;">
            <h2>📡 <?php _e('API Endpoints', 'woo-products-api'); ?></h2>
            <p><strong><?php _e('Base URL: ', 'woo-products-api'); ?></strong><code><?php echo esc_html($base_url); ?></code></p>
            
            <h3>🟣 <?php _e('All Products API (الجديد)', 'woo-products-api'); ?></h3>
            <p style="background: #e7f5ff; padding: 10px; border-left: 4px solid #1e90ff;">
                <strong>📌 الميزة الجديدة:</strong> روت موحد لعرض جميع المنتجات (الفيزيائية والمتغيرة) مع دعم كامل للصفحات والبحث والفلترة
            </p>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th style="width: 100px;"><?php _e('Method', 'woo-products-api'); ?></th>
                        <th><?php _e('Endpoint', 'woo-products-api'); ?></th>
                        <th><?php _e('Description', 'woo-products-api'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="dashicons dashicons-list-view"></span> GET</td>
                        <td><code>/products</code></td>
                        <td><?php _e('عرض جميع المنتجات (فيزيائية + متغيرة) مع pagination + filters', 'woo-products-api'); ?></td>
                    </tr>
                    <tr>
                        <td><span class="dashicons dashicons-search"></span> GET</td>
                        <td><code>/products/search</code></td>
                        <td><?php _e('البحث في جميع المنتجات مع pagination', 'woo-products-api'); ?></td>
                    </tr>
                    <tr>
                        <td><span class="dashicons dashicons-visibility"></span> GET</td>
                        <td><code>/products/{id}</code></td>
                        <td><?php _e('عرض منتج واحد (أي نوع)', 'woo-products-api'); ?></td>
                    </tr>
                    <tr>
                        <td><span class="dashicons dashicons-chart-bar"></span> GET</td>
                        <td><code>/products/stats</code></td>
                        <td><?php _e('إحصائيات شاملة لجميع المنتجات', 'woo-products-api'); ?></td>
                    </tr>
                </tbody>
            </table>
            
            <div style="background: #fff9e6; padding: 15px; margin: 10px 0; border-left: 4px solid #ffa500;">
                <h4 style="margin-top: 0;">🎯 <?php _e('معاملات الفلترة المتاحة:', 'woo-products-api'); ?></h4>
                <ul style="list-style: disc; margin-left: 20px;">
                    <li><code>?page=1</code> - رقم الصفحة</li>
                    <li><code>&per_page=10</code> - عدد المنتجات في الصفحة</li>
                    <li><code>&type=all|physical|variable</code> - نوع المنتج</li>
                    <li><code>&status=publish|draft|any</code> - حالة المنتج</li>
                    <li><code>&featured=true</code> - المنتجات المميزة فقط</li>
                    <li><code>&on_sale=true</code> - المنتجات المخفضة فقط</li>
                    <li><code>&category=category-slug</code> - فلترة حسب التصنيف</li>
                    <li><code>&orderby=date|title|price</code> - ترتيب النتائج</li>
                    <li><code>&order=ASC|DESC</code> - اتجاه الترتيب</li>
                </ul>
            </div>
            
            <h3 style="margin-top: 30px;">🔵 <?php _e('Physical Products API', 'woo-products-api'); ?></h3>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th style="width: 100px;"><?php _e('Method', 'woo-products-api'); ?></th>
                        <th><?php _e('Endpoint', 'woo-products-api'); ?></th>
                        <th><?php _e('Description', 'woo-products-api'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="dashicons dashicons-plus"></span> POST</td>
                        <td><code>/physical-products</code></td>
                        <td><?php _e('إضافة منتج فيزيائي جديد (مع صور)', 'woo-products-api'); ?></td>
                    </tr>
                    <tr>
                        <td><span class="dashicons dashicons-update"></span> POST</td>
                        <td><code>/physical-products/{id}</code></td>
                        <td><?php _e('تعديل منتج فيزيائي (مع صور)', 'woo-products-api'); ?></td>
                    </tr>
                    <tr>
                        <td><span class="dashicons dashicons-list-view"></span> GET</td>
                        <td><code>/physical-products</code></td>
                        <td><?php _e('عرض جميع المنتجات الفيزيائية (pagination)', 'woo-products-api'); ?></td>
                    </tr>
                    <tr>
                        <td><span class="dashicons dashicons-visibility"></span> GET</td>
                        <td><code>/physical-products/{id}</code></td>
                        <td><?php _e('عرض منتج فيزيائي واحد', 'woo-products-api'); ?></td>
                    </tr>
                    <tr>
                        <td><span class="dashicons dashicons-search"></span> GET</td>
                        <td><code>/physical-products/search</code></td>
                        <td><?php _e('البحث عن منتجات فيزيائية', 'woo-products-api'); ?></td>
                    </tr>
                    <tr>
                        <td><span class="dashicons dashicons-trash"></span> DELETE</td>
                        <td><code>/physical-products/{id}</code></td>
                        <td><?php _e('حذف منتج فيزيائي', 'woo-products-api'); ?></td>
                    </tr>
                    <tr>
                        <td><span class="dashicons dashicons-chart-bar"></span> GET</td>
                        <td><code>/physical-products/stats</code></td>
                        <td><?php _e('إحصائيات المنتجات الفيزيائية', 'woo-products-api'); ?></td>
                    </tr>
                </tbody>
            </table>
            
            <h3 style="margin-top: 30px;">🟢 <?php _e('Variable Products API', 'woo-products-api'); ?></h3>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th style="width: 100px;"><?php _e('Method', 'woo-products-api'); ?></th>
                        <th><?php _e('Endpoint', 'woo-products-api'); ?></th>
                        <th><?php _e('Description', 'woo-products-api'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="dashicons dashicons-plus"></span> POST</td>
                        <td><code>/variable-products</code></td>
                        <td><?php _e('إضافة منتج متغير جديد (مع صور)', 'woo-products-api'); ?></td>
                    </tr>
                    <tr>
                        <td><span class="dashicons dashicons-update"></span> POST</td>
                        <td><code>/variable-products/{id}</code></td>
                        <td><?php _e('تعديل منتج متغير (مع صور)', 'woo-products-api'); ?></td>
                    </tr>
                    <tr>
                        <td><span class="dashicons dashicons-list-view"></span> GET</td>
                        <td><code>/variable-products</code></td>
                        <td><?php _e('عرض جميع المنتجات المتغيرة (pagination)', 'woo-products-api'); ?></td>
                    </tr>
                    <tr>
                        <td><span class="dashicons dashicons-visibility"></span> GET</td>
                        <td><code>/variable-products/{id}</code></td>
                        <td><?php _e('عرض منتج متغير واحد', 'woo-products-api'); ?></td>
                    </tr>
                    <tr>
                        <td><span class="dashicons dashicons-search"></span> GET</td>
                        <td><code>/variable-products/search</code></td>
                        <td><?php _e('البحث عن منتجات متغيرة', 'woo-products-api'); ?></td>
                    </tr>
                    <tr>
                        <td><span class="dashicons dashicons-trash"></span> DELETE</td>
                        <td><code>/variable-products/{id}</code></td>
                        <td><?php _e('حذف منتج متغير', 'woo-products-api'); ?></td>
                    </tr>
                    <tr>
                        <td><span class="dashicons dashicons-chart-bar"></span> GET</td>
                        <td><code>/variable-products/stats</code></td>
                        <td><?php _e('إحصائيات المنتجات المتغيرة', 'woo-products-api'); ?></td>
                    </tr>
                </tbody>
            </table>
            
            <div style="padding: 20px; background: #fff3cd; border-left: 4px solid #ffc107; margin: 20px 0;">
                <h3>🔒 <?php _e('Authentication', 'woo-products-api'); ?></h3>
                <p><?php _e('جميع الروابط محمية وتتطلب مفاتيح WooCommerce API للوصول:', 'woo-products-api'); ?></p>
                <ul style="list-style: disc; margin-left: 20px;">
                    <li><?php _e('استخدم WooCommerce Consumer Key و Consumer Secret', 'woo-products-api'); ?></li>
                    <li><?php _e('أضف المفاتيح في Headers باستخدام Basic Authentication', 'woo-products-api'); ?></li>
                    <li><?php _e('تحقق من صلاحيات المستخدم (manage_woocommerce)', 'woo-products-api'); ?></li>
                </ul>
            </div>
            
            <div style="padding: 20px; background: #d1ecf1; border-left: 4px solid #17a2b8; margin: 20px 0;">
                <h3>📦 <?php _e('Postman Collection', 'woo-products-api'); ?></h3>
                <p><?php _e('تم إنشاء ملف Postman Collection كامل في:', 'woo-products-api'); ?></p>
                <code><?php echo esc_html(WOO_PRODUCTS_API_DIR . 'postman/'); ?></code>
                <p style="margin-top: 10px;"><?php _e('استيراد الملف في Postman لتجربة جميع الروابط مع بيانات تجريبية جاهزة.', 'woo-products-api'); ?></p>
            </div>

            <div style="padding: 20px; background: #d4edda; border-left: 4px solid #28a745; margin: 20px 0;">
                <h3>🖼️ <?php _e('دعم الصور', 'woo-products-api'); ?></h3>
                <p><?php _e('يمكنك رفع الصور مباشرة عبر Form Data:', 'woo-products-api'); ?></p>
                <ul style="list-style: disc; margin-left: 20px;">
                    <li><strong>main_image</strong>: <?php _e('الصورة الرئيسية للمنتج', 'woo-products-api'); ?></li>
                    <li><strong>gallery_images[]</strong>: <?php _e('صور المعرض (متعددة)', 'woo-products-api'); ?></li>
                    <li><?php _e('استخدم multipart/form-data بدلاً من JSON', 'woo-products-api'); ?></li>
                </ul>
            </div>
        </div>
    </div>
    <?php
}

// Activation hook
register_activation_hook(__FILE__, 'woo_products_api_activate');

function woo_products_api_activate() {
    if (!class_exists('WooCommerce')) {
        deactivate_plugins(plugin_basename(__FILE__));
        wp_die(__('This plugin requires WooCommerce to be installed and active.', 'woo-products-api'));
    }
    
    // Flush rewrite rules
    flush_rewrite_rules();
}

// Deactivation hook
register_deactivation_hook(__FILE__, 'woo_products_api_deactivate');

function woo_products_api_deactivate() {
    // Flush rewrite rules
    flush_rewrite_rules();
}
