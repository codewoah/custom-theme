<?php

use App\Klaviyo;
use Siro\Klaviyo\KlaviyoAPI;
use function App\add_fit_finder_kit;
use function App\sage;

add_action('wp_ajax_willo_add_cart','willo_add_cart');
add_action('wp_ajax_nopriv_willo_add_cart','willo_add_cart');
function willo_add_cart() {
    session_start();
    WC()->cart->empty_cart();
    $other_need_product_add_cart = sage('config')->get('theme')['side_product_add_cart'];
    foreach ($other_need_product_add_cart as $product_name => $product) {
        switch ($product_name) {
            case 'fit_finder_kit':
                add_fit_finder_kit( $product , wc_get_product($_POST['product']));
                break;
            case 'essential_set':
                WC()->cart->add_to_cart( $product );
                break;
        }
    }
    $_SESSION['later_subscription'] = $_POST['product'];
    wp_send_json_success();
}



add_action('wp_ajax_willo_update_sidecart','update_sidecart');
add_action('wp_ajax_nopriv_willo_update_sidecart','update_sidecart');
function update_sidecart(){
    $cart = WC()->cart->get_cart_contents();
    echo \App\template('partials/woocommerce/cart-content',compact('cart'));
    die();
}

add_action('wp_ajax_open_pioneer_modal','open_pioneer_modal');
add_action('wp_ajax_nopriv_open_pioneer_modal','open_pioneer_modal');
function open_pioneer_modal(){
    $cart = WC()->cart->get_cart_contents();
    echo \App\template('partials/woocommerce/cart-content',['cart' => $cart, 'target' => $_POST['target']]);
    die();
}

add_action('wp_ajax_checkout_next_steps','checkout_next_steps');
add_action('wp_ajax_nopriv_checkout_next_steps','checkout_next_steps');
function checkout_next_steps() {
    $view = $_POST['view'];
    $success = false;
    $fragment = null;
    switch ($view) {
        case 'checkout-shipping':
            if( is_user_logged_in() ) {
                $success = true;
                $fragment = \App\template('partials/woocommerce/checkout/steps/' . $view, array('checkout' => WC_Checkout::instance()));
            } else {
                $success = false;
                $fragment = __('You need to be login to go to this steps');
            }
            break;
        case 'checkout-payment':
            $success = true;
            break;
        case 'login-form':
            if( !is_user_logged_in() ) {
                $success = true;
                $fragment = \App\template('partials/woocommerce/checkout/steps/' . $view, array('checkout' => WC_Checkout::instance()));
            } else {
                $success = false;

            }
            break;
        default:
            $success = false;
            break;
    }

    wp_send_json(array(
        'success' => $success,
        'fragment' =>$fragment
    ));
}

/** Login ajax */
add_action('wp_ajax_willo_signin','willo_signin');
add_action('wp_ajax_nopriv_willo_signin','willo_signin');
function willo_signin() {
    $creds = array(
        'user_login'    => $_POST['login'],
        'user_password' => $_POST['password'],
        'remember'      => true
    );


    $user = wp_signon( $creds, false );

    if ( is_wp_error( $user ) ) {
        wp_send_json_error($user->get_error_message());
    } else {
        wp_send_json_success();
    }


}

add_action('wp_ajax_willo_search_variation','willo_search_variation');
add_action('wp_ajax_nopriv_willo_search_variation','willo_search_variation');
function willo_search_variation() {
    wp_reset_query();
    $query = new \WP_Query( array(
        'post_parent' => sage('config')->get('theme')['fresh_routine_parent_variation'],
        'post_status' => 'publish',
        'post_type' => 'product_variation',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key'   => 'attribute_size',
                'value' => $_POST['size'],
            ),
            array(
                'key'   => 'attribute_billing-period',
                'value' => $_POST['period'],
            )
        ),
    ));
    if($query->have_posts()){
        wp_send_json_success($query->post->ID);
    }
    wp_send_json_error();
}


add_action('wp_ajax_willo_update_sizes','willo_update_sizes');
add_action('wp_ajax_nopriv_willo_update_sizes','willo_update_sizes');
function willo_update_sizes() {
    $data = json_decode(stripslashes($_POST['data']), true);
    $order_id = $_POST['order_id'];
    /** @var WC_Order $order */
    $order = wc_get_order($order_id);
    $order->update_meta_data('_mouth_sizes', $data);
    $order->update_meta_data('_mouth_sizes_completed', true);
    $order->set_status('completed');
    if( $order->save() ) {
        do_action('willo_user_successfuly_update_sizes',$order);
        wp_send_json_success();
    }

    wp_send_json_error();
}

add_action('wp_ajax_willo_validate_pionner_code','willo_validate_pionner_code');
add_action('wp_ajax_nopriv_willo_validate_pionner_code','willo_validate_pionner_code');
function willo_validate_pionner_code() {
    $required_code = get_field('willo_pioneer_code','option');

    if(!\App\is_in_stock(App\sage('config')->get('theme')['side_product_add_cart']['essential_set']))
    {
        wp_send_json_error(__('Sorry willo is out of stock', 'willo'));
    }

    if($required_code === $_POST['code'] && !empty($_POST['code'])) {
        setcookie("willo_pioneer_program_ok",'OK',time()+31556926 ,'/','', true);// where 31556926 is total seconds for a year.
        wp_send_json_success();
    }else {
        wp_send_json_error(__('Pioneer code is invalid, please check your invitation email', 'willo'));
    }
}


add_action('wp_ajax_willo_edit_delivery_date','willo_edit_delivery_date');
add_action('wp_ajax_nopriv_willo_edit_delivery_date','willo_edit_delivery_date');
function willo_edit_delivery_date() {
    \App\edit_delivery_date(
        esc_attr($_POST['date']),
        esc_attr($_POST['subscription'])
    );
    wp_send_json(
        wc_get_endpoint_url( 'view-subscription',  $_POST['subscription'], wc_get_page_permalink( 'myaccount' ) )
    );
}

add_action('wp_ajax_apply_coupon_cart','apply_coupon_cart');
add_action('wp_ajax_nopriv_apply_coupon_cart','apply_coupon_cart');
function apply_coupon_cart() {
    /** @var WC_Discounts */
    $coupon = new WC_Coupon($_POST['code']);
    $discounts = new WC_Discounts( WC()->cart );
    if( !is_wp_error( $discounts->is_coupon_valid($coupon) ) ) {
        WC()->cart->apply_coupon($_POST['code']);
        wp_send_json_success($_POST['code']);
    } else {
        wp_send_json_error('Your code is expired or not applicable to this order');
        wc_clear_notices();
    }

    wp_send_json_error();

}

add_action('wp_ajax_remove_coupon_cart','remove_coupon_cart');
add_action('wp_ajax_nopriv_remove_coupon_cart','remove_coupon_cart');
function remove_coupon_cart() {
    WC()->cart->remove_coupon($_POST['code']);
    WC()->cart->calculate_totals();
    wp_send_json($_POST['code']);
}

add_action('wp_ajax_recalculate_cart','recalculate_cart');
add_action('wp_ajax_nopriv_recalculate_cart','recalculate_cart');
function recalculate_cart() {
    $cart = WC()->cart->get_cart_contents();
    echo \App\template('partials/woocommerce/cart-content',compact('cart'));
    die();
}

add_action('wp_ajax_track_order','track_order');
function track_order() {
    echo \App\template(
        'partials/woocommerce/account/modal-tracking-content',
        ['status' => esc_attr($_POST['status'])]
    );
    die();
}


add_action('wp_ajax_empty_cart','empty_cart');
add_action('wp_ajax_nopriv_empty_cart','empty_cart');
function empty_cart() {
    WC()->cart->empty_cart();
    die();
}


add_action('wp_ajax_cancel_subscription','cancel_subscription');
add_action('wp_ajax_nopriv_cancel_subscription','cancel_subscription');
function cancel_subscription() {
    $sub_id = esc_attr($_POST['subscription']);
    /** @var WC_Subscription $subscription */
    $subscription = wcs_get_subscription($sub_id);
    if( $subscription ) {
        try {
            $subscription->update_status('cancelled');
            if( $subscription->save() ) {
                wp_send_json_success(
                    esc_url( wc_get_endpoint_url( 'subscriptions',  '', wc_get_page_permalink( 'myaccount' ) ) )
                );
            }
        } catch (Exception $e) {
            wp_send_json_error($e);
        }
    }

    wp_send_json_error();

}

add_action('wp_ajax_reactivate_subscription','reactivate_subscription');
add_action('wp_ajax_nopriv_reactivate_subscription','reactivate_subscription');
function reactivate_subscription() {
    $sub_id = esc_attr($_POST['subscription']);
    /** @var WC_Subscription $subscription */
    $subscription = wcs_get_subscription($sub_id);
    if( $subscription ) {
        try {
            $subscription->update_status('active');
            if( $subscription->save() ) {
                wp_send_json_success(
                    esc_url( wc_get_endpoint_url( 'subscriptions',  '', wc_get_page_permalink( 'myaccount' ) ) )
                );
            }
        } catch (Exception $e) {
            wp_send_json_error($e->getMessage());
        }
    }

    wp_send_json_error();

}

/**
 * Allow '+' sign in email addresses
 */
function modern_email_validation($is_email, $email, $context) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }
    return true;
}
add_filter('is_email', 'modern_email_validation', 99, 3);

add_action('wp_ajax_nopriv_willo_signup_customer','willo_signup_customer');
function willo_signup_customer() {
    if( !is_email(esc_attr($_POST['login'])) ) {
        wp_send_json_error('Hold up! We???re gonna need an email.');

    }

    $userdata = array(
        'user_login' =>  esc_attr($_POST['login']),
        'user_email' =>  esc_attr($_POST['login']),
        'user_pass' => esc_attr($_POST['password']),
    );

    $user_id = wp_insert_user( $userdata ) ;

    if ( is_wp_error( $user_id ) ) {
        wp_send_json_error($user_id->get_error_message());
    } else {
        $user = wp_signon( array(
            'user_login'    => esc_attr($_POST['login']),
            'user_email'    => esc_attr($_POST['login']),
            'user_password' => esc_attr($_POST['password']),
            'remember'      => true
        ), false );

        if ( is_wp_error( $user ) ) {
            wp_send_json_error($user->get_error_message());
        }


        do_action('willo_user_signup_successfully', $user, esc_attr($_POST['subscribe']));

        wp_send_json_success($user);

    }

    die();

}

add_action('wp_ajax_edit_user_profile','edit_user_profile');
function edit_user_profile()
{
    /** @var WP_User $user */
    $user = get_user_by('id',get_current_user_id());
    $user_data = array(
        'ID' => get_current_user_id(),
        'first_name' => esc_attr($_POST['user_first_name']),
        'last_name' => esc_attr($_POST['user_last_name']),
        'user_email' => esc_attr($_POST['user_email'])
    );

    if (
        !empty($_POST['user_current_password']) &&
        !empty($_POST['user_new_password']) &&
        !empty($_POST['user_confirm_new_password'])
    ) {
        if( !wp_check_password( esc_attr($_POST['user_current_password']) , $user->data->user_pass, $user->ID ) ) {
            wp_send_json_error(
                'Wrong current password'
            );
        } elseif(
            esc_attr($_POST['user_new_password']) === esc_attr($_POST['user_confirm_new_password'])
        ) {
            if( strlen($_POST['user_new_password']) >= 8 ) {
                $user_data = array_merge(
                    $user_data,
                    array(
                        'user_pass' => esc_attr($_POST['user_new_password'])
                    )
                );
            } else {
                wp_send_json_error(
                    'Password must be at least 8 characters'
                );
            }

        } else {
            wp_send_json_error(
                'Passwords doesn\'t match'
            );
        }
    }

    $result = wp_update_user($user_data);

    if ( !is_wp_error( $result ) ) {
        wp_send_json_success();
    }

    wp_send_json_error($result->get_error_message());
}

add_action('wp_ajax_edit_billing_shipping_addresses','edit_billing_shipping_addresses');
function edit_billing_shipping_addresses(){
    /** @var WC_Customer $customer */
    $customer = new WC_Customer(get_current_user_id());
    if( $_POST['type'] === 'shipping' ) {
        $customer->set_shipping_last_name(esc_attr($_POST['shipping_last_name_field']));
        $customer->set_shipping_first_name(esc_attr($_POST['shipping_first_name_field']));
        $customer->set_shipping_address_1(esc_attr($_POST['shipping_address_1_field']));
        $customer->set_shipping_address_2(esc_attr($_POST['shipping_address_2_field']));
        $customer->set_shipping_country(esc_attr($_POST['shipping_country_field']));
        $customer->set_shipping_state(esc_attr($_POST['shipping_state_field']));
        $customer->set_shipping_postcode(esc_attr($_POST['shipping_postcode_field']));
    } else {
        $customer->set_billing_last_name(esc_attr($_POST['billing_last_name_field']));
        $customer->set_billing_first_name(esc_attr($_POST['billing_first_name_field']));
        $customer->set_billing_address_1(esc_attr($_POST['billing_address_1_field']));
        $customer->set_billing_address_2(esc_attr($_POST['billing_address_2_field']));
        $customer->set_billing_country(esc_attr($_POST['billing_country_field']));
        $customer->set_billing_state(esc_attr($_POST['billing_state_field']));
        $customer->set_billing_postcode(esc_attr($_POST['billing_postcode_field']));
    }

    wp_send_json($customer->save());
}

add_action('wp_ajax_klaviyo_optin_newsletter','klaviyo_optin_newsletter');
add_action('wp_ajax_nopriv_klaviyo_optin_newsletter','klaviyo_optin_newsletter');
function klaviyo_optin_newsletter() {
    $email = esc_attr($_POST['email']);
    $klaviyo = new KlaviyoAPI(sage('config')->get('theme')['klaviyo']['api_key']);

    $realIP = file_get_contents("http://ipecho.net/plain");

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://freegeoip.app/json/$realIP",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "accept: application/json",
            "content-type: application/json"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        wp_send_json_error();

    } else {
        $result = json_decode($response, true);
        $result = $klaviyo->list->addMember(
            sage('config')->get('theme')['klaviyo']['newsletter_list_id'],
            array([
                'email' => $email,
                '$country' => $result['country_name'] ,
                '$region' => $result['region_name'] ,
                '$city' => $result['city'] ,
                '$timezone' => $result['time_zone']
            ])
        );

        if($result[0]->id) {
            wp_send_json_success($result[0]->id);
        } else {
            wp_send_json_error();
        }
    }

    die();
}

add_action('wp_ajax_klaviyo_store_how_heard','klaviyo_store_how_heard');
function klaviyo_store_how_heard() {
    /** @var WC_Order $order */
    $order =  wc_get_order(esc_attr($_POST['order_id']));
    $response = esc_attr($_POST['response']);
    $email = $order->get_billing_email();
    $klaviyo = new KlaviyoAPI(sage('config')->get('theme')['klaviyo']['api_key']);
    $klaviyo_profile = Klaviyo::get_user($email);
    if( is_array($klaviyo_profile) && isset($klaviyo_profile['id']) ) {
        $result = $klaviyo->profile->edit( $klaviyo_profile['id'] , array(
            'How you heard' => $response
        ));

        if( $result->id ) {
            wp_send_json_success();
        }
    }
    else {
        wp_send_json_success();
    }

    die();
}


