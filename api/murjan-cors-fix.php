<?php
/**
 * Plugin Name: Murjan CORS Fix - حل فوري لمشاكل CORS
 * Description: يحل مشكلة CORS للوصول إلى API من Vercel والنطاقات الأخرى
 * Version: 1.0.0
 * Author: Murjan Team
 * 
 * ⚠️ طريقة التثبيت:
 * 1. ارفع هذا الملف إلى: wp-content/mu-plugins/murjan-cors-fix.php
 * 2. أو أضف هذا السطر في wp-config.php قبل "That's all":
 *    require_once(ABSPATH . 'wp-content/plugins/woo-products-api/murjan-cors-fix.php');
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * ✅ إرسال CORS Headers بشكل فوري
 */
function murjan_send_cors_headers() {
    // 🌐 النطاقات المسموح لها
    $allowed_origins = [
        // Production
        'https://woo-4pdx.vercel.app',
        'https://woo-silk.vercel.app',
        'https://dev.murjan.sa',
        'https://murjan.sa',
        'https://www.murjan.sa',
        
        // Development
        'http://localhost:3000',
        'http://localhost:5173',
        'http://localhost:5174',
        'http://127.0.0.1:3000',
        'http://127.0.0.1:5173',
    ];
    
    // Get request origin
    $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
    
    // ✅ Send headers for allowed origins
    if (in_array($origin, $allowed_origins)) {
        header("Access-Control-Allow-Origin: {$origin}");
        header("Access-Control-Allow-Credentials: true");
    } elseif (!empty($origin)) {
        // Allow any origin in development
        header("Access-Control-Allow-Origin: {$origin}");
        header("Access-Control-Allow-Credentials: true");
    } else {
        // Fallback - allow all
        header("Access-Control-Allow-Origin: *");
    }
    
    // ✅ Allow all necessary methods
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, PATCH");
    
    // ✅ Allow all necessary headers
    header("Access-Control-Allow-Headers: Authorization, Content-Type, X-WP-Nonce, X-Requested-With, Accept, Origin, X-Api-Key");
    
    // ✅ Expose custom headers
    header("Access-Control-Expose-Headers: X-WP-Total, X-WP-TotalPages");
    
    // ✅ Cache preflight for 24 hours
    header("Access-Control-Max-Age: 86400");
    
    // ✅ Handle OPTIONS preflight
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        status_header(200);
        exit(0);
    }
}

// 🚀 Hook #1: Very early - before WordPress loads
add_action('init', 'murjan_send_cors_headers', 0);

// 🚀 Hook #2: When headers are sent
add_action('send_headers', 'murjan_send_cors_headers', 0);

// 🚀 Hook #3: REST API specific
add_action('rest_api_init', function() {
    // Remove default WordPress CORS handler
    remove_filter('rest_pre_serve_request', 'rest_send_cors_headers');
    
    // Add our custom handler
    add_filter('rest_pre_serve_request', function($served) {
        murjan_send_cors_headers();
        return $served;
    }, 1);
}, 0);

// 🚀 Hook #4: Authentication errors (critical!)
add_filter('rest_authentication_errors', function($errors) {
    // Always send CORS headers even on auth errors
    murjan_send_cors_headers();
    return $errors;
}, 0);

// 🚀 Hook #5: Pre-dispatch (before request is processed)
add_filter('rest_pre_dispatch', function($result, $server, $request) {
    murjan_send_cors_headers();
    return $result;
}, 0, 3);

// 📝 Debug logging (disable in production)
if (defined('WP_DEBUG') && WP_DEBUG) {
    add_action('rest_api_init', function() {
        error_log('=== MURJAN CORS DEBUG ===');
        error_log('Origin: ' . (isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : 'NOT SET'));
        error_log('Method: ' . $_SERVER['REQUEST_METHOD']);
        error_log('URI: ' . $_SERVER['REQUEST_URI']);
    });
}

/**
 * ✅ إضافة ملاحظة في لوحة التحكم
 */
add_action('admin_notices', function() {
    if (current_user_can('manage_options')) {
        ?>
        <div class="notice notice-success is-dismissible">
            <p><strong>✅ Murjan CORS Fix Active:</strong> CORS headers are being sent for API requests from Vercel and other allowed origins.</p>
        </div>
        <?php
    }
});

