<?php
/**
 * Main plugin bootstrap.
 *
 * @package SEO_Master
 */

namespace SEO_Master;

defined( 'EXIT_SCRIPT' ) || define( 'EXIT_SCRIPT', true );

class Main {

    private static $instance;

    public $db;
    public $output;
    public $admin;
    public $rest;
    public $migration;

    private function __construct() {
        add_action( 'plugins_loaded', [ $this, 'init' ] );
        add_action( 'admin_menu', [ $this, 'admin_menu' ] );
    }

    public static function instance() {
        if ( self::$instance === null ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function activate() {
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        $db = new Database();
        $db->create_tables();
        $db->install_defaults();

        update_option( 'seo_master_version', SEO_MASTER_VERSION );
        update_option( 'seo_master_initialized', '1' );
        update_option( 'seo_master_db_version', '1.0.0' );
    }

    public static function deactivate() {
        delete_transient( 'seo_master_yoast_detection' );
    }

    public static function uninstall() {
        global $wpdb;

        $tables = [
            "{$wpdb->prefix}seo_master_indexables",
            "{$wpdb->prefix}seo_master_indexable_hierarchy",
            "{$wpdb->prefix}seo_master_primary_term",
            "{$wpdb->prefix}seo_master_seo_links",
            "{$wpdb->prefix}seo_master_prominent_words",
            "{$wpdb->prefix}seo_master_settings",
            "{$wpdb->prefix}seo_master_redirects",
            "{$wpdb->prefix}seo_master_schema",
            "{$wpdb->prefix}seo_master_sitemaps",
            "{$wpdb->prefix}seo_master_migrations",
            "{$wpdb->prefix}seo_master_id_map",
        ];

        foreach ( $tables as $table ) {
            $wpdb->query( "DROP TABLE IF EXISTS `{$table}`" );
        }

        $options = $wpdb->get_results(
            "SELECT option_name FROM {$wpdb->options}
             WHERE option_name LIKE 'seo_master%'",
            ARRAY_A
        );
        foreach ( $options as $option ) {
            delete_option( $option['option_name'] );
        }
    }

    public function init() {
        $this->db       = new Database();
        $this->output   = new Output();
        $this->admin    = new Admin();
        $this->rest     = new REST();
        $this->migration = new Migration();

        $this->output->init();
        $this->admin->init();
        $this->rest->init();

        $this->maybe_migrate();
    }

    public function admin_menu() {
        add_menu_page(
            'SEO Master',
            'SEO Master',
            'manage_options',
            'seo-master',
            [ $this->admin, 'render_dashboard' ],
            'dashicons-search',
            6
        );
    }

    private function maybe_migrate() {
        $completed = get_option( 'seo_master_migration_completed', '' );
        if ( $completed === '1' ) {
            return;
        }

        $detection = $this->migration->detect_yoast();
        if ( $detection['has_yoast'] ) {
            update_option( 'seo_master_yoast_detected', '1' );
            update_option( 'seo_master_migration_status', 'pending' );
        } else {
            update_option( 'seo_master_migration_completed', '1' );
        }
    }
}

Main::instance();