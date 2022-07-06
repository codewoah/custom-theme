<?php

use App\Wc\Emails\WC_Delivered_Order_Email;
use App\Wc\Emails\WC_OutDelivery_Order_Email;
use App\Wc\Emails\WC_Shipped_Order_Email;
use function App\is_subscription;
use function App\template;

add_filter( 'woocommerce_enable_order_notes_field', '__return_false' );
add_filter('woocommerce_cart_needs_shipping_address', '__return_true');
add_filter('woocommerce_cart_needs_shipping', '__return_true');

add_filter('wc_stripe_display_save_payment_method_checkbox', '__return_false');
add_filter( 'wc_stripe_force_save_source', '__return_true' );


function plugin_republic_add_cart_item_data( $cart_item_data, $product_id, $variation_id ) {
    if( isset( $_POST['subscription-id'] ) ) {
        $cart_item_data['subscription-id'] = sanitize_text_field( $_POST['subscription-id'] );
    }
    return $cart_item_data;
}
add_filter( 'woocommerce_add_cart_item_data', 'plugin_republic_add_cart_item_data', 10, 3 );


add_filter( 'woocommerce_get_order_item_totals', 'custom_woocommerce_get_order_item_totals' );

function custom_woocommerce_get_order_item_totals( $totals ) {
    unset( $totals['payment_method'] );
    $totals['shipping'] = array(
        'label' => 'Shipping',
        'value' => 'Free'
    );

    $fields = array(
        'cart_subtotal',
        'shipping',
        'order_total',
    );

    // Updating the 'priority' argument
    foreach($fields as $value){
        $ordered_fields[$value] = $totals[$value];
    }

    return $ordered_fields;
}

add_filter('woocommerce_cart_item_subtotal',function ($total,$cart_item){
    if(is_subscription($cart_item['variation_id'])) {
        $total .= sprintf(__('<span style="text-decoration: line-through;display: block;margin-top: 4px">%s</span>'),\App\calc_percentage_saved($cart_item['variation_id']));
    }
    return $total;
},10,2);

add_filter( 'wc_order_statuses', function($order_statuses){
    $new_order_statuses = array();

    // add new order status after processing
    foreach ( $order_statuses as $key => $status ) {

        $new_order_statuses[ $key ] = $status;

        if ( 'wc-processing' === $key ) {
            $new_order_statuses['wc-shipped'] = _x( 'Shipped', 'Order status', 'woocommerce' );
            $new_order_statuses['wc-delivered'] = _x( 'Delivered', 'Order status', 'woocommerce' );
            $new_order_statuses['wc-out-delivery'] = _x( 'Out for delivery', 'Order status', 'woocommerce' );
        }

        if ( 'wc-completed' === $key ) {
            $order_statuses['wc-completed'] = _x( 'Order Received', 'Order status', 'woocommerce' );
        }
    }

    return $new_order_statuses;
});


add_filter('woocommerce_endpoint_lost-password_title',function(){
    return __('Forgot your password','willo');
});

add_filter('pre_get_document_title', function(){
    if( is_wc_endpoint_url('lost-password') ) {
        if( isset($_GET['show-reset-form']) ) {
            return __('Reset your password','willo');
        }
        return 'Forgot password - willo';
    }
    if( is_wc_endpoint_url('orders') ) {
        return 'Orders - willo';
    }
    if( is_wc_endpoint_url('view-order') ) {
        return 'Order details - willo';
    }
    if( is_wc_endpoint_url('subscriptions') ) {
        return 'Your subscriptions - willo';
    }
    if( is_wc_endpoint_url('view-subscription') ) {
        return 'Subscription details - willo';
    }
    if( is_wc_endpoint_url('edit-account') ) {
        return 'My profile - willo';
    }
    if( strpos($_SERVER['REQUEST_URI'], 'order-update-mouthpiece-size') ) {
        return 'Update your size - willo';
    }
    if( strpos($_SERVER['REQUEST_URI'], 'modify-subscription-plan') ) {
        return 'Modify your plan - willo';
    }
    if(
        is_wc_endpoint_url('edit-address') ||
        strpos($_SERVER['REQUEST_URI'], 'edit-address-billing') ||
        strpos($_SERVER['REQUEST_URI'], 'edit-address-shipping') ||
        is_wc_endpoint_url('payment-methods')
    ) {
        return 'Billing / Shipping - willo';
    }
}, 9999 );


add_filter('woocommerce_checkout_fields', function($fields){

    unset(
        $fields['billing']['billing_company'],
        $fields['billing']['billing_email'],
        $fields['shipping']['shipping_email'],
        $fields['shipping']['shipping_company'],
    );


    $fields['billing']['billing_first_name']['placeholder'] = 'First Name';
    $fields['billing']['billing_last_name']['placeholder'] = 'Last Name';
    $fields['billing']['billing_address_1']['placeholder'] = 'Street address';
    $fields['billing']['billing_address_2']['placeholder'] = 'Apartment / Suite (optional)';
    $fields['billing']['billing_city']['placeholder'] = 'City';
    $fields['billing']['billing_state']['placeholder'] = 'State';
    $fields['billing']['billing_postcode']['placeholder'] = 'Postcode';
    $fields['billing']['billing_phone']['placeholder'] = 'Phone number';


    $fields['shipping']['shipping_first_name']['placeholder'] = 'First Name';
    $fields['shipping']['shipping_last_name']['placeholder'] = 'Last Name';
    $fields['shipping']['shipping_address_1']['placeholder'] = 'Street address';
    $fields['shipping']['shipping_address_2']['placeholder'] = 'Apartment / Suite (optional)';
    $fields['shipping']['shipping_city']['placeholder'] = 'City';
    $fields['shipping']['shipping_state']['placeholder'] = 'State';
    $fields['shipping']['shipping_postcode']['placeholder'] = 'Postcode';
    $fields['shipping']['shipping_phone']['placeholder'] = 'Phone number';



    return $fields;

},10,1);

add_filter('woocommerce_default_address_fields', 'override_default_address_checkout_fields', 20, 1);
function override_default_address_checkout_fields( $address_fields ) {
    $address_fields['address_1']['placeholder'] = 'Street address';
    $address_fields['address_2']['placeholder'] = 'Apartment / Suite (optional)';
    return $address_fields;
}

add_filter('woocommerce_form_field_custom_check',function($field, $key, $args, $value){
    return '
        <p id="'.esc_attr( $args['id'] ) . '_field'.'" data-priority="' . esc_attr( $args['priority'] ) . '" class="input-checkbox ' . esc_attr( implode( ' ', $args['class'] ) ) . '">
            <input checked name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" value="1" type="checkbox" for="shipping_sms" />
            <label class="checkbox" for="'. esc_attr( $args['id'] ) .'">Receive order updates by sms</label>
        </p>
    ';
},200,4);

add_filter('woocommerce_checkout_cart_item_quantity',function(){
    return '';
});

add_filter('woocommerce_order_item_name', function($product_name, $item){
    if( WC_Subscriptions_Product::is_subscription($item['variation_id']) ) {
        $variation_object = wc_get_product_variation_attributes($item['variation_id']);
        $product_name =  '<span class="willo_product_name_email">'. $variation_object['attribute_size'] .' '. $product_name . '<br>';
        $product_name .= sprintf(__('%s plan </span>','willo'), $variation_object['attribute_billing-period'] === 'Year' ? 'Annual' : 'Every 3 months');
    } else {
        $product_name = $item['quantity'].' '.$product_name;
    }

    return $product_name;
  },10,2);

add_filter('woocommerce_cart_item_name',function($product_name, $cart_item, $cart_item_key){
    echo template(
        'partials/woocommerce/cart_product_name',
        array(
            'data' => array(
                'name' => $product_name,
                'cart_item' => $cart_item,
                'cart_item_key' => $cart_item_key,
            )
        )
    );
},10,3);

add_filter( 'woocommerce_order_button_text', 'checkout_button_text' );

function checkout_button_text( $button_text ) {
    return 'Place order'; // new text is here
}

/**
 * @param $price
 * @param WC_Product_Variable $instance
 * @return string
 */
function filter_woocommerce_get_price_html( $price, $instance ) {
    if( !is_admin() ) {
        $period = WC_Subscriptions_Product::get_period($instance);
        if( WC_Subscriptions_Product::is_subscription($instance) ) {
            $size_package = (int) $instance->get_data()['attributes']['size'];
            if( $size_package ) {
                if( $period === 'month' ) {
                    $per_month = number_format(($instance->get_price() / 3 ) /$size_package , 2);
                    $price = sprintf( __('$%s - Total <strong> $%s</strong>'), $per_month.'/mo', $instance->get_price() );
                } else {
                    $per_year = number_format(($instance->get_price() / 12)/$size_package, 2);
                    $price = sprintf( __('%s - Total <strong> $%s</strong>'), $per_year.'/mo', $instance->get_price()  );
                }
            }

            return $price;
        }
    }

    return $price;
}

// add the filter
add_filter( 'woocommerce_get_price_html', 'filter_woocommerce_get_price_html', 10, 2 );

add_filter('woocommerce_available_payment_gateways','filter_gateways',1);
function filter_gateways($gateways){
    if(WC()->cart && WC()->cart->get_cart_contents_count() === 1) {
        if( wcs_cart_contains_renewal() ) {
            unset($gateways['klarna']);
        }
    }

    return $gateways;
}

add_filter('woocommerce_gateway_icon',function($icons) {
    return '<div class="gateway_icons">'.$icons.'</div>';
});

add_filter('wcs_my_account_redirect_to_single_subscription', '__return_false');

add_filter ( 'woocommerce_account_menu_items', 'remove_my_account_links' );
function remove_my_account_links($menu_links){
    unset(
        $menu_links['downloads'],
        $menu_links['dashboard'],
        $menu_links['payment-methods']
    ); // Addresses


   return array(
        'orders'             => __( 'Orders', 'woocommerce' ),
        'subscriptions'             => __( 'Subscriptions', 'woocommerce' ),

        'edit-account'    	=> __( 'My Profile', 'woocommerce' ),
        'edit-address'       => __( 'Billing / Shipping', 'woocommerce' ),
        'customer-logout'    => __( 'Logout', 'woocommerce' ),
    );

}

add_filter('woocommerce_my_account_my_orders_columns',function($columns){
    $header[] = 'Order';
    $header[] = 'Status';
    $header[] = 'Order date';
    $header[] = 'Total';

    return $header;
});

/* Redirects to the Orders List instead of Woocommerce My Account Dashboard */
function woocommerce_account_redirect() {

    $current_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

    $dashboard_url = get_permalink( get_option('woocommerce_myaccount_page_id'));

    if(is_user_logged_in() && $dashboard_url == $current_url){
        $url = get_home_url() . '/my-account/orders';
        wp_redirect( $url );
        exit;
    }

}
add_action('template_redirect', 'woocommerce_account_redirect');

/* Remove the Dashboard tab of the My Account Page */
function custom_my_account_menu_items( $items ) {
    unset($items['dashboard']);
    return $items;
}
add_filter( 'woocommerce_account_menu_items', 'custom_my_account_menu_items' );

add_filter( 'woocommerce_order_item_get_formatted_meta_data', 'unset_specific_order_item_meta_data', 10, 2);

function unset_specific_order_item_meta_data($formatted_meta, $item){
    foreach( $formatted_meta as $key => $meta ){
       if( $meta->key === '_size_kit_order_related' ) {
           $meta->display_key = 'Size kit for order';
           $meta->display_value = sprintf(__('%s'),'<a target="_blank" href="'.get_edit_post_link($meta->value).'">'.$meta->value.'</a>');
       }


    }
    return $formatted_meta;
}


add_filter( 'woocommerce_email_order_items_args', 'iconic_email_order_items_args', 10, 1 );

function iconic_email_order_items_args( $args ) {
    $args['show_image'] = true;

    return $args;
}


/**
 * @param $email_classes
 * @return mixed
 */
function add_order_status_woocommerce_email($email_classes ) {

    // include our custom email class

    // add the email class to the list of email classes that WooCommerce loads
    $email_classes['WC_Shipped_Order_Email'] = new WC_Shipped_Order_Email();
    $email_classes['WC_OutDelivery_Order_Email'] = new WC_OutDelivery_Order_Email();
    $email_classes['WC_Delivered_Order_Email'] = new WC_Delivered_Order_Email();

    return $email_classes;

}
add_filter( 'woocommerce_email_classes', 'add_order_status_woocommerce_email' );

/** EMAILS */
add_filter('woocommerce_email_order_item_quantity', '__return_empty_string');
/** /EMAILS */

add_filter( 'woocommerce_email_recipient_customer_processing_order', 'disable_customer_order_email_if_free', 10, 2 );
/**
 * @param $recipient
 * @param WC_Order $order
 * @return string
 */
function disable_customer_order_email_if_free( $recipient, $order ) {

    if ( $order->has_free_item() && count($order->get_items()) === 1 ) {
        $recipient = '';
    }

    return $recipient;
}

add_filter('woocommerce_email_subject_customer_processing_order',function(){
   return get_field('processing_email_subject','option');
});

