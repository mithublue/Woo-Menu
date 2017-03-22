<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WCM_Page_Placeholder {

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
    public function __construct() {
        //shop, category, archive
        add_action('woocommerce_archive_description',array( $this, 'add_sidebar_before_main_content' ) );/*woocommerce_before_main_content*/
        //single
        add_action('woocommerce_before_single_product',array( $this, 'add_sidebar_before_main_content' ) );
        //checkout
        add_action('woocommerce_before_checkout_form',array( $this, 'add_sidebar_before_main_content' ) );
        //my account
        add_action('woocommerce_before_my_account',array( $this, 'add_sidebar_before_main_content' ) );
        //cart
        add_action('woocommerce_before_cart',array( $this, 'add_sidebar_before_main_content' ) );

    }


    public function add_sidebar_before_main_content( $data ) {
        dynamic_sidebar( 'wcm-sidebar' );
        return $data;
    }
}

WCM_Page_Placeholder::get_instance();