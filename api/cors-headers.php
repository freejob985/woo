<?php
/**
 * CORS Headers Handler
 * Add this file to fix CORS issues immediately
 * 
 * Installation:
 * 1. Upload this file to wp-content/mu-plugins/
 * 2. Or include it in wp-config.php: require_once('path/to/cors-headers.php');
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    // If not in WordPress context, define basic CORS
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');
    }
    
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
            header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, PATCH');
        }
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
            header('Access-Control-Allow-Headers: Authorization, Content-Type, X-WP-Nonce, X-Requested-With, Accept, Origin, X-Requested-With');
        }
        header('Access-Control-Expose-Headers: X-WP-Total, X-WP-TotalPages');
        http_response_code(200);
        exit(0);
    }
    return;
}

/**
 * Function to send CORS headers
 */
function send_cors_headers() {
    // List of allowed origins (add your domains here)
    $allowed_origins = array(
        'https://woo-silk.vercel.app',
        'https://dev.murjan.sa',
        'http://localhost:3000',
        'http://localhost:5173',
        'http://localhost:5174',
        'http://localhost:5175',
        'http://127.0.0.1:3000',
        'http://127.0.0.1:5173',
    );
    
    // Get the origin of the request
    $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
    
    // Check if origin is allowed or allow all for development
    if (in_array($origin, $allowed_origins) || !empty($origin)) {
        header('Access-Control-Allow-Origin: ' . $origin);
        header('Access-Control-Allow-Credentials: true');
    } else {
        // Allow all origins as fallback
        header('Access-Control-Allow-Origin: *');
    }
    
    header('Access-Control-Max-Age: 86400'); // cache for 1 day
    
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
            header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, PATCH');
        }
        
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
            header('Access-Control-Allow-Headers: ' . $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']);
        } else {
            header('Access-Control-Allow-Headers: Authorization, Content-Type, X-WP-Nonce, X-Requested-With, Accept, Origin, X-Api-Key');
        }
        
        header('Access-Control-Expose-Headers: X-WP-Total, X-WP-TotalPages, X-WP-TotalPages');
        
        // Return 200 for preflight
        http_response_code(200);
        exit(0);
    }
}

// Hook into WordPress init - highest priority
add_action('init', 'send_cors_headers', 1);

// Also hook into send_headers
add_action('send_headers', 'send_cors_headers', 1);

// Hook into rest_api_init for REST API
add_action('rest_api_init', function() {
    remove_filter('rest_pre_serve_request', 'rest_send_cors_headers');
    add_filter('rest_pre_serve_request', function($value) {
        $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
        
        if (!empty($origin)) {
            // Allow the specific origin that made the request
            header('Access-Control-Allow-Origin: ' . $origin);
            header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, PATCH');
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Allow-Headers: Authorization, Content-Type, X-WP-Nonce, X-Requested-With, Accept, Origin, X-Api-Key');
            header('Access-Control-Expose-Headers: X-WP-Total, X-WP-TotalPages');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            header('Access-Control-Max-Age: 86400');
            status_header(200);
            exit();
        }
        
        return $value;
    });
}, 15);

// Additional filter for authentication errors
add_filter('rest_authentication_errors', function($result) {
    if (!empty($result)) {
        return $result;
    }
    
    // Force CORS headers even on auth errors
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Credentials: true');
    }
    
    return $result;
});

// Log CORS activity (comment out in production)
add_action('rest_api_init', function() {
    if (defined('WP_DEBUG') && WP_DEBUG) {
        error_log('CORS Headers enabled for: ' . $_SERVER['REQUEST_URI']);
        error_log('Origin: ' . (isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : 'not set'));
        error_log('Method: ' . $_SERVER['REQUEST_METHOD']);
    }
});

