<?php
/**
 * Adds Foo_Widget widget.
 */
class WooCommerce_Menu_Widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    function __construct() {
        parent::__construct(
            'WooCommerce_Menu_Widget', // Base ID
            esc_html__( 'WooCommerce Menu', 'wcm' ), // Name
            array( 'description' => esc_html__( 'Add widget to be visible to the WooCommerce pages as you want', 'wcm' ), ) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $i ) {

        global $wp_query, $product;

        if(  !is_realy_woocommerce_page() ) return;

        if( is_shop() || is_product_category() || is_product_tag() ) {
            $page_id = get_option( 'woocommerce_shop_page_id' );
        } else if ( is_product() ) {
            $page_id = $product->ID;
        } elseif ( is_cart() ) {
            $page_id = get_option( 'woocommerce_cart_page_id' );
        } elseif ( is_checkout() ) {
            $page_id  = get_option( 'woocommerce_checkout_page_id' );
        } elseif ( is_account_page() ) {
            $page_id = get_option( 'woocommerce_myaccount_page_id' );
        }

        if ( !in_array( $page_id , $i['wcm_page'] ) ) {
            return;
        }

        echo $args['before_widget'];
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $i['title'] ) . $args['after_title'];
        }


        wp_nav_menu( array(
            'theme_location' => $i['wcm_menu'],
            'menu_class' => $i['wcm_menu_style']
        ) );

        //code
        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'New title', 'text_domain' );
        !isset($instance['wcm_page'])? $instance['wcm_page'] = array() : '' ;
        !isset($instance['wcm_menu_style'])? $instance['wcm_menu_style'] = 'wcm-menu-default' : '' ;
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'text_domain' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <?php
        $page_ids = array();
        $page_ids[] = get_option( 'woocommerce_shop_page_id' );
        $page_ids[] = get_option( 'woocommerce_cart_page_id' );
        $page_ids[] = get_option( 'woocommerce_checkout_page_id' );
        $page_ids[] = get_option( 'woocommerce_pay_page_id' );
        $page_ids[] = get_option( 'woocommerce_thanks_page_id' );
        $page_ids[] = get_option( 'woocommerce_myaccount_page_id' );
        $page_ids[] = get_option( 'woocommerce_edit_address_page_id' );
        $page_ids[] = get_option( 'woocommerce_view_order_page_id' );
        $page_ids[] = get_option( 'woocommerce_terms_page_id' );
        $all_wc_pages = new WP_Query(array(
            'post__in' => $page_ids,
            'post_type' => 'page'
        ));
        ?>
        <div>
            <h4><?php _e( 'Choose page where the menu should be displayed', 'wcm' ); ?></h4>
            <?php
            if ( $all_wc_pages->have_posts() ) :
                while ( $all_wc_pages->have_posts() ) : $all_wc_pages->the_post();
                    ?>
                    <p><input type="checkbox"
                                  id="<?php echo esc_attr( $this->get_field_id( 'wcm_page' ) ); ?>"
                                  name="<?php echo esc_attr( $this->get_field_name( 'wcm_page' ) ); ?>[]"
                                  value="<?php echo get_the_ID(); ?>"
                              <?php echo in_array( get_the_ID(), $instance['wcm_page'] ) ? 'checked' : ''; ?>
                        ><?php the_title(); ?></p>
                    <?php
                endwhile;
            endif;
            // Reset Post Data
            wp_reset_postdata();
            ?>
        </div>
        <div>
            <h4><?php _e( 'Choose the menu that should be displayed', 'wcm' ); ?></h4>
            <?php
            $menus = get_registered_nav_menus();
            ?>
            <select
                id="<?php echo esc_attr( $this->get_field_id( 'wcm_menu' ) ); ?>"
                name="<?php echo esc_attr( $this->get_field_name( 'wcm_menu' ) ); ?>"
            >
                <?php
                foreach ( $menus as $location => $description ) {
                    echo '<option value="'.$location . '"  '. ( $instance['wcm_menu'] == $location ? 'selected' : '' ).' >' . $description . '</option>';
                }
                ?>
            </select>
        </div>
        <div>
            <h4><?php _e( 'Choose style' , 'wcm' ); ?></h4>
            <?php
            $styles = apply_filters( 'wcm_menu_style', array(
                'wcm-menu-default' => 'Default'
            ) );

            ?>
            <select
                name="<?php echo esc_attr( $this->get_field_name( 'wcm_menu_style' ) ); ?>"
                id="<?php echo esc_attr( $this->get_field_id( 'wcm_menu_style' ) ); ?>">
                <option value=""><?php _e('None', 'wcm'); ?></option>
                <?php
                foreach ( $styles as $style => $name ) {
                    ?>
                    <option value="<?php echo $style; ?>"
                    <?php echo $instance['wcm_menu_style'] == $style ? 'selected' : '' ; ?>
                    ><?php echo $name; ?>
                    </option>
                    <?php
                }
                ?>
            </select>
        </div>
<?php

    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $ni, $oi ) {
        $i = array();
        $i['title'] = ( ! empty( $ni['title'] ) ) ? strip_tags( $ni['title'] ) : '';
        $i['wcm_page'] = ( ! empty( $ni['wcm_page'] ) ) ? $ni['wcm_page'] : array();
        $i['wcm_menu'] = ( ! empty( $ni['wcm_menu'] ) ) ? strip_tags( $ni['wcm_menu'] ) : '';
        $i['wcm_menu_style'] = ( ! empty( $ni['wcm_menu_style'] ) ) ? strip_tags( $ni['wcm_menu_style'] ) : '';

        return $i;
    }

} // class WooCommerce_Menu_Widget

// register Foo_Widget widget
function wcm_register_widget() {
    register_widget( 'WooCommerce_Menu_Widget' );
}
add_action( 'widgets_init', 'wcm_register_widget' );