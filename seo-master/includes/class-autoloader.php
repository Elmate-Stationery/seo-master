<?php
/**
 * SEO Master PSR-4 Autoloader.
 *
 * @package SEO_Master
 */

defined( 'EXIT_SCRIPT' ) || define( 'EXIT_SCRIPT', true );

if ( class_exists( 'SEO_Master_Autoloader' ) ) {
    return;
}

class SEO_Master_Autoloader {

    private $prefix = 'SEO_Master\\';
    private $prefix_length = 11;

    public function __construct() {
        spl_autoload_register( [ $this, 'load_class' ] );
    }

    public function load_class( $class ) {
        if ( strncmp( $this->prefix, $class, $this->prefix_length ) !== 0 ) {
            return;
        }

        $relative = substr( $class, $this->prefix_length );
        $relative = str_replace( '\\', '/', $relative );
        $relative = str_replace( '_', '-', $relative );
        $file = SEO_MASTER_PATH . 'includes/' . $relative . '.php';

        if ( is_readable( $file ) ) {
            require $file;
            return;
        }
    }
}

new SEO_Master_Autoloader();