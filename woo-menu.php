<?php
/*
 * Plugin Name: Woo Menu
 * Plugin URI: http://cybercraftit.com/woo-menu/
 * Description: Add custom navigation/menu bar to your woocommerce pages
 * Author: Mithu A Quayium
 * Author URI: http://cybercraftit.com/
 * Version: 1.0
 * Text Domain: wcm
 * License: GPL2
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Required minimums and constants
 */
define( 'WCM_VERSION', '1.0' );
define( 'WCM_ROOT', dirname(__FILE__) );
define( 'WCM_ASSET_PATH', plugins_url('assets',__FILE__) );

require_once WCM_ROOT.'/menu-widget.php';

class WCM_Init {

    /**
     * @var Singleton The reference the *Singleton* instance of this class
     */
    private static $instance;

    /**
     * Returns the *Singleton* instance of this class.
     *
     * @return Singleton The *Singleton* instance.
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Protected constructor to prevent creating a new instance of the
     * *Singleton* via the `new` operator from outside of this class.
     */
    protected function __construct() {
        $this->include_files();
        add_action('wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts_styles' ) );
    }

    public function include_files() {
        require_once WCM_ROOT.'/functions.php';
        require_once WCM_ROOT.'/page-placeholders.php';
    }

    public function wp_enqueue_scripts_styles() {
        include_once WCM_ROOT.'/style.php';
    }
}

WCM_Init::get_instance();