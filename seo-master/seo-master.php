<?php
/**
 * Plugin Name: SEO Master
 * Description: Independent SEO platform. Manages titles, meta descriptions, canonicals, robots, schema, sitemaps, and redirects. Works from zero or imports existing Yoast SEO data.
 * Version: 1.0.0
 * Author: SEO Master
 * Author URI:  https://example.com/seo-master
 * Plugin URI:   https://example.com/seo-master
 * Text Domain: seo-master
 * Domain Path: /languages
 * License:     GPL v3
 * Requires at least: 6.8
 * Requires PHP: 7.4
 *
 * @package SEO_Master
 */

defined( 'EXIT_SCRIPT' ) || define( 'EXIT_SCRIPT', true );
defined( 'SEO_MASTER_FILE' ) || define( 'SEO_MASTER_FILE', __FILE__ );
defined( 'SEO_MASTER_PATH' ) || define( 'SEO_MASTER_PATH', plugin_dir_path( SEO_MASTER_FILE ) );
defined( 'SEO_MASTER_BASENAME' ) || define( 'SEO_MASTER_BASENAME', plugin_basename( SEO_MASTER_FILE ) );
defined( 'SEO_MASTER_VERSION' ) || define( 'SEO_MASTER_VERSION', '1.0.0' );
defined( 'SEO_MASTER_NAMESPACES' ) || define( 'SEO_MASTER_NAMESPACES', true );
defined( 'SEO_MASTER_VENDOR_PREFIX' ) || define( 'SEO_MASTER_VENDOR_PREFIX', 'SEO_Master_' );

if ( ! is_readable( SEO_MASTER_PATH . 'vendor/autoload.php' ) ) {
    require_once SEO_MASTER_PATH . 'includes/class-autoloader.php';
}

require_once SEO_MASTER_PATH . 'includes/functions.php';
require_once SEO_MASTER_PATH . 'includes/class-main.php';

register_activation_hook( SEO_MASTER_FILE, [ 'SEO_Master\Main', 'activate' ] );
register_deactivation_hook( SEO_MASTER_FILE, [ 'SEO_Master\Main', 'deactivate' ] );
register_uninstall_hook( SEO_MASTER_FILE, [ 'SEO_Master\Main', 'uninstall' ] );