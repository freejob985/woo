<?php
/**
 * Uninstall WooCommerce Products Importer
 *
 * Deletes all plugin data when the plugin is uninstalled
 *
 * @package WooCommerce_Products_Importer
 */


// Exit if accessed directly or not in uninstall context
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Delete plugin options
delete_option('wpi_imported_products');

// Delete any transients
delete_transient('wpi_import_status');

// Clean up any remaining data
global $wpdb;

// Optional: Delete all imported products
// Uncomment if you want products to be deleted on plugin uninstall
/*
$imported_ids = get_option('wpi_imported_products', array());
if (!empty($imported_ids)) {
    foreach ($imported_ids as $product_id) {
        wp_delete_post($product_id, true);
    }
}
*/


// Delete plugin meta
$wpdb->query("DELETE FROM {$wpdb->postmeta} WHERE meta_key LIKE 'wpi_%'");

// Clear any cached data
wp_cache_flush();


