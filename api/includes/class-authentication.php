<?php
/**
 * Authentication Class
 * Handles WooCommerce API authentication and authorization
 */

if (!defined('ABSPATH')) {
    exit;
}

class WOO_Products_API_Authentication {
    
    /**
     * Verify WooCommerce API credentials
     */
    public static function verify_woocommerce_auth($request) {
        // Get authentication header
        $auth_header = $request->get_header('authorization');
        
        if (!$auth_header) {
            // Try to get consumer key and secret from parameters
            $consumer_key = $request->get_param('consumer_key');
            $consumer_secret = $request->get_param('consumer_secret');
            
            if ($consumer_key && $consumer_secret) {
                return self::validate_keys($consumer_key, $consumer_secret);
            }
            
            return new WP_Error(
                'woo_api_auth_missing',
                __('Authentication credentials are missing.', 'woo-products-api'),
                array('status' => 401)
            );
        }
        
        // Parse Basic Authentication
        if (strpos($auth_header, 'Basic') === 0) {
            $credentials = base64_decode(substr($auth_header, 6));
            list($consumer_key, $consumer_secret) = explode(':', $credentials, 2);
            
            return self::validate_keys($consumer_key, $consumer_secret);
        }
        
        return new WP_Error(
            'woo_api_auth_invalid',
            __('Invalid authentication method.', 'woo-products-api'),
            array('status' => 401)
        );
    }
    
    /**
     * Validate WooCommerce API keys
     */
    private static function validate_keys($consumer_key, $consumer_secret) {
        global $wpdb;
        
        // Clean the consumer key (remove ck_ prefix if exists)
        $consumer_key = wc_api_hash($consumer_key);
        
        // Query the database for API key
        $key_data = $wpdb->get_row($wpdb->prepare(
            "SELECT key_id, user_id, permissions, consumer_key, consumer_secret, nonces 
            FROM {$wpdb->prefix}woocommerce_api_keys 
            WHERE consumer_key = %s",
            $consumer_key
        ));
        
        if (!$key_data) {
            return new WP_Error(
                'woo_api_auth_invalid_key',
                __('Invalid consumer key.', 'woo-products-api'),
                array('status' => 401)
            );
        }
        
        // Verify consumer secret
        if (!hash_equals($key_data->consumer_secret, $consumer_secret)) {
            return new WP_Error(
                'woo_api_auth_invalid_secret',
                __('Invalid consumer secret.', 'woo-products-api'),
                array('status' => 401)
            );
        }
        
        // Check if key has read/write permissions
        if ($key_data->permissions !== 'read_write' && $key_data->permissions !== 'write') {
            return new WP_Error(
                'woo_api_auth_insufficient_permissions',
                __('API key does not have sufficient permissions.', 'woo-products-api'),
                array('status' => 403)
            );
        }
        
        // Check if user has manage_woocommerce capability
        $user = get_user_by('id', $key_data->user_id);
        if (!$user || !$user->has_cap('manage_woocommerce')) {
            return new WP_Error(
                'woo_api_auth_user_permissions',
                __('User does not have sufficient permissions.', 'woo-products-api'),
                array('status' => 403)
            );
        }
        
        return true;
    }
    
    /**
     * Permission callback for REST API endpoints
     */
    public static function check_permission($request) {
        $auth_result = self::verify_woocommerce_auth($request);
        
        if (is_wp_error($auth_result)) {
            return $auth_result;
        }
        
        return true;
    }
}

