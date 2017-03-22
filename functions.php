<?php
add_action( 'widgets_init', 'wcm_widgets_init' );

function wcm_widgets_init() {
    register_sidebar( array(
        'name'          => __( 'WooCommerce Navigation Sidebar', 'wcm' ),
        'id'            => 'wcm-sidebar',
        'description'   => __( 'Sidebar to add woocommerce navigation', 'wcm' ),
        'before_widget' => '<section id="%1$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );
}


/**
 * is_realy_woocommerce_page - Returns true if on a page which uses WooCommerce templates (cart and checkout are standard pages with shortcodes and which are also included)
 *
 * @access public
 * @return bool
 */
function is_realy_woocommerce_page () {
    if(  function_exists ( "is_woocommerce" ) && is_woocommerce()){
        return true;
    }

    $woocommerce_keys   =   array ( "woocommerce_shop_page_id" ,
        "woocommerce_terms_page_id" ,
        "woocommerce_cart_page_id" ,
        "woocommerce_checkout_page_id" ,
        "woocommerce_pay_page_id" ,
        "woocommerce_thanks_page_id" ,
        "woocommerce_myaccount_page_id" ,
        "woocommerce_edit_address_page_id" ,
        "woocommerce_view_order_page_id" ,
        "woocommerce_change_password_page_id" ,
        "woocommerce_logout_page_id" ,
        "woocommerce_lost_password_page_id" ) ;
    foreach ( $woocommerce_keys as $wc_page_id ) {
        if ( get_the_ID () == get_option ( $wc_page_id , 0 ) ) {
            return true ;
        }
    }
    return false;
}

// woocommerce nav menu
register_nav_menus( array(
    'woo-menu' => 'Woo Menu'
));