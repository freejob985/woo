<?php
/**
 * Plugin Name: WooCommerce Products Importer
 * Plugin URI: https://example.com
 * Description: A plugin to add 40 products (20 Physical and 20 Variable) to WooCommerce
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://example.com
 * Text Domain: woo-products-importer
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

// Check if WooCommerce is active
if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    add_action('admin_notices', 'wpi_woocommerce_missing_notice');
    return;
}

function wpi_woocommerce_missing_notice() {
    ?>
    <div class="error">
        <p><?php _e('WooCommerce Products Importer requires WooCommerce to be installed and active.', 'woo-products-importer'); ?></p>
    </div>
    <?php
}

// Define plugin constants
define('WPI_VERSION', '1.0.0');
define('WPI_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WPI_PLUGIN_URL', plugin_dir_url(__FILE__));

// Add admin menu
add_action('admin_menu', 'wpi_add_admin_menu');

function wpi_add_admin_menu() {
    add_menu_page(
        __('Products Importer', 'woo-products-importer'),
        __('Products Importer', 'woo-products-importer'),
        'manage_woocommerce',
        'woo-products-importer',
        'wpi_admin_page',
        'dashicons-cart',
        56
    );
}

// Admin page content
function wpi_admin_page() {
    ?>
    <div class="wrap">
        <h1><?php _e('WooCommerce Products Importer', 'woo-products-importer'); ?></h1>
        
        <?php
        if (isset($_POST['wpi_import_products']) && check_admin_referer('wpi_import_products_action', 'wpi_import_products_nonce')) {
            $result = wpi_import_products();
            if ($result['success']) {
                ?>
                <div class="notice notice-success is-dismissible">
                    <p><?php echo sprintf(__('Successfully imported %d products!', 'woo-products-importer'), $result['count']); ?></p>
                </div>
                <?php
            } else {
                ?>
                <div class="notice notice-error is-dismissible">
                    <p><?php echo $result['message']; ?></p>
                </div>
                <?php
            }
        }
        
        if (isset($_POST['wpi_delete_products']) && check_admin_referer('wpi_delete_products_action', 'wpi_delete_products_nonce')) {
            $result = wpi_delete_imported_products();
            if ($result['success']) {
                ?>
                <div class="notice notice-success is-dismissible">
                    <p><?php echo sprintf(__('Successfully deleted %d products!', 'woo-products-importer'), $result['count']); ?></p>
                </div>
                <?php
            }
        }
        ?>
        
        <div class="card" style="max-width: 800px;">
            <h2><?php _e('Import Products', 'woo-products-importer'); ?></h2>
            <p><?php _e('This plugin will import 40 products into your WooCommerce store:', 'woo-products-importer'); ?></p>
            <ul style="list-style: disc; margin-left: 20px;">
                <li><?php _e('20 Physical Products - Tangible items that get delivered to customers', 'woo-products-importer'); ?></li>
                <li><?php _e('20 Variable Products - Products with variations like color or size', 'woo-products-importer'); ?></li>
            </ul>
            
            <form method="post" style="margin-top: 20px;">
                <?php wp_nonce_field('wpi_import_products_action', 'wpi_import_products_nonce'); ?>
                <button type="submit" name="wpi_import_products" class="button button-primary button-large">
                    <?php _e('Import 40 Products', 'woo-products-importer'); ?>
                </button>
            </form>
            
            <hr style="margin: 30px 0;">
            
            <h3><?php _e('Delete Imported Products', 'woo-products-importer'); ?></h3>
            <p><?php _e('Remove all products that were imported by this plugin.', 'woo-products-importer'); ?></p>
            
            <form method="post">
                <?php wp_nonce_field('wpi_delete_products_action', 'wpi_delete_products_nonce'); ?>
                <button type="submit" name="wpi_delete_products" class="button button-secondary" 
                        onclick="return confirm('<?php _e('Are you sure you want to delete all imported products?', 'woo-products-importer'); ?>');">
                    <?php _e('Delete All Imported Products', 'woo-products-importer'); ?>
                </button>
            </form>
        </div>
    </div>
    <?php
}

// Import products function
function wpi_import_products() {
    $imported_count = 0;
    $imported_ids = array();
    
    try {
        // Import 20 Physical Products
        for ($i = 1; $i <= 20; $i++) {
            $product_id = wpi_create_physical_product($i);
            if ($product_id) {
                $imported_ids[] = $product_id;
                $imported_count++;
            }
        }
        
        // Import 20 Variable Products
        for ($i = 1; $i <= 20; $i++) {
            $product_id = wpi_create_variable_product($i);
            if ($product_id) {
                $imported_ids[] = $product_id;
                $imported_count++;
            }
        }
        
        // Save imported product IDs for later deletion
        update_option('wpi_imported_products', $imported_ids);
        
        return array(
            'success' => true,
            'count' => $imported_count
        );
        
    } catch (Exception $e) {
        return array(
            'success' => false,
            'message' => $e->getMessage()
        );
    }
}

// Create a physical product
function wpi_create_physical_product($index) {
    $product = new WC_Product_Simple();
    
    // Set product data
    $product->set_name("Physical Product #{$index}");
    $product->set_slug("physical-product-{$index}");
    $product->set_status('publish');
    $product->set_catalog_visibility('visible');
    $product->set_description("This is a detailed description for Physical Product #{$index}. It's a tangible item that gets delivered to customers.");
    $product->set_short_description("High-quality physical product #{$index} delivered to your door.");
    
    // Set pricing
    $price = rand(10, 200);
    $product->set_regular_price($price);
    $product->set_price($price);
    
    // Set stock
    $product->set_manage_stock(true);
    $product->set_stock_quantity(rand(50, 200));
    $product->set_stock_status('instock');
    
    // Set shipping
    $product->set_weight(rand(100, 2000) / 100); // Weight in kg
    $product->set_length(rand(10, 50));
    $product->set_width(rand(10, 50));
    $product->set_height(rand(5, 30));
    
    // Set SKU
    $product->set_sku("PHYS-{$index}-" . time());
    
    // Set virtual and downloadable to false for physical products
    $product->set_virtual(false);
    $product->set_downloadable(false);
    
    // Save product
    $product_id = $product->save();
    
    // Add to category
    wp_set_object_terms($product_id, 'Physical Products', 'product_cat', true);
    
    return $product_id;
}

// Create a variable product with variations
function wpi_create_variable_product($index) {
    $product = new WC_Product_Variable();
    
    // Set product data
    $product->set_name("Variable Product #{$index}");
    $product->set_slug("variable-product-{$index}");
    $product->set_status('publish');
    $product->set_catalog_visibility('visible');
    $product->set_description("This is a detailed description for Variable Product #{$index}. Available in multiple colors and sizes.");
    $product->set_short_description("Premium variable product #{$index} with multiple options.");
    
    // Set SKU
    $product->set_sku("VAR-{$index}-" . time());
    
    // Save parent product
    $product_id = $product->save();
    
    // Define attributes
    $colors = array('Red', 'Blue', 'Green', 'Black', 'White');
    $sizes = array('S', 'M', 'L', 'XL');
    
    // Create Color attribute
    $color_attribute = new WC_Product_Attribute();
    $color_attribute->set_name('Color');
    $color_attribute->set_options($colors);
    $color_attribute->set_position(0);
    $color_attribute->set_visible(true);
    $color_attribute->set_variation(true);
    
    // Create Size attribute
    $size_attribute = new WC_Product_Attribute();
    $size_attribute->set_name('Size');
    $size_attribute->set_options($sizes);
    $size_attribute->set_position(1);
    $size_attribute->set_visible(true);
    $size_attribute->set_variation(true);
    
    // Set attributes to product
    $product->set_attributes(array($color_attribute, $size_attribute));
    $product->save();
    
    // Create variations
    $variation_count = 0;
    foreach ($colors as $color) {
        foreach ($sizes as $size) {
            // Limit to 8 variations per product
            if ($variation_count >= 8) break 2;
            
            $variation = new WC_Product_Variation();
            $variation->set_parent_id($product_id);
            
            // Set variation attributes
            $variation->set_attributes(array(
                'Color' => $color,
                'Size' => $size
            ));
            
            // Set pricing
            $price = rand(15, 250);
            $variation->set_regular_price($price);
            $variation->set_price($price);
            
            // Set stock
            $variation->set_manage_stock(true);
            $variation->set_stock_quantity(rand(20, 100));
            $variation->set_stock_status('instock');
            
            // Set SKU
            $variation->set_sku("VAR-{$index}-{$color}-{$size}-" . time());
            
            // Save variation
            $variation->save();
            $variation_count++;
        }
    }
    
    // Sync product with variations
    WC_Product_Variable::sync($product_id);
    
    // Add to category
    wp_set_object_terms($product_id, 'Variable Products', 'product_cat', true);
    
    return $product_id;
}

// Delete imported products
function wpi_delete_imported_products() {
    $imported_ids = get_option('wpi_imported_products', array());
    $deleted_count = 0;
    
    foreach ($imported_ids as $product_id) {
        if (wp_delete_post($product_id, true)) {
            $deleted_count++;
        }
    }
    
    // Clear the saved IDs
    delete_option('wpi_imported_products');
    
    return array(
        'success' => true,
        'count' => $deleted_count
    );
}

// Activation hook
register_activation_hook(__FILE__, 'wpi_activate');

function wpi_activate() {
    if (!class_exists('WooCommerce')) {
        deactivate_plugins(plugin_basename(__FILE__));
        wp_die(__('This plugin requires WooCommerce to be installed and active.', 'woo-products-importer'));
    }
}

// Deactivation hook
register_deactivation_hook(__FILE__, 'wpi_deactivate');

function wpi_deactivate() {
    // Cleanup if needed
}

