<?php

namespace App;

use Roots\Sage\Container;
use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\VersionStrategy\JsonManifestVersionStrategy;

/**
 * Get the sage container.
 *
 * @param string $abstract
 * @param array  $parameters
 * @param Container $container
 * @return Container|mixed
 */
function sage($abstract = null, $parameters = [], Container $container = null)
{
    $container = $container ?: Container::getInstance();
    if (!$abstract) {
        return $container;
    }
    return $container->bound($abstract)
        ? $container->makeWith($abstract, $parameters)
        : $container->makeWith("sage.{$abstract}", $parameters);
}

function is_subscription($product)
{
    return \WC_Subscriptions_Product::is_subscription($product);
}

/**
 * Get / set the specified configuration value.
 *
 * If an array is passed as the key, we will assume you want to set an array of values.
 *
 * @param array|string $key
 * @param mixed $default
 * @return mixed|\Roots\Sage\Config
 * @copyright Taylor Otwell
 * @link https://github.com/laravel/framework/blob/c0970285/src/Illuminate/Foundation/helpers.php#L254-L265
 */
function config($key = null, $default = null)
{
    if (is_null($key)) {
        return sage('config');
    }
    if (is_array($key)) {
        return sage('config')->set($key);
    }
    return sage('config')->get($key, $default);
}

/**
 * @param string $file
 * @param array $data
 * @return string
 */
function template($file, $data = [])
{
    return sage('blade')->render($file, $data);
}

/**
 * Retrieve path to a compiled blade view
 * @param $file
 * @param array $data
 * @return string
 */
function template_path($file, $data = [])
{
    return sage('blade')->compiledPath($file, $data);
}

function is_prod_only()
{
    return $_SERVER['HTTP_HOST'] === 'willo.com';
}

function is_dev()
{
    return $_SERVER['HTTP_HOST'] === 'dev.cosavostra.com';
}

function isLocalhost($whitelist = ['127.0.0.1', '::1'])
{
    return in_array($_SERVER['REMOTE_ADDR'], $whitelist);
}

/**
 * @param $asset
 * @return string
 */
function asset_path($asset)
{
    if (file_exists(get_template_directory() . '/dist/manifest.json')) {
        $package = new Package(new JsonManifestVersionStrategy(get_template_directory() . '/dist/manifest.json'));
        if (is_dev()) {
            return str_replace('willo', '', home_url()) . $package->getUrl($asset);
        }

        return $package->getUrl($asset);
    }
}

function get_token($token)
{
    global $wpdb;
    $token_id = $wpdb->get_results(
        $wpdb->prepare("SELECT token_id  FROM {$wpdb->prefix}woocommerce_payment_tokens WHERE token=%s", $token)
    );
    if ($token_id) {
        if ($token_id[0]->token_id) {
            return $wpdb->get_results(
                $wpdb->prepare("SELECT meta_key,meta_value  FROM {$wpdb->prefix}woocommerce_payment_tokenmeta WHERE payment_token_id=%s",  $token_id[0]->token_id)
            );
        }
    }



    return null;
}

function get_card_meta($infos, $key)
{
    if ($infos) {
        foreach ($infos as $info) {
            if ($info->meta_key === $key) {
                return $info->meta_value;
            }
        }
    }
}

function calc_percentage_saved($variant)
{

    /** @var \WC_Product_Subscription_Variation $product */
    $product = wc_get_product($variant);
    $billing_period = $product->get_data()['attributes']['billing-period'];
    $size = $product->get_data()['attributes']['size'];

    $price_text =  '';

    if ($billing_period === 'Month' && $size === '1') {
        return $price_text;
    }

    $default_price_month = (int) wc_get_product(sage('config')->get('theme')['default_plan']['Month'])->get_price();
    $default_price_year =  (int) wc_get_product(sage('config')->get('theme')['default_plan']['Year'])->get_price();

    if ($billing_period === 'Month') {
        $cost = wc_price($default_price_month * (int) $size);
        $price_text = "${cost} every 3 months";
    } else {
        if ($size === '1') {
            $cost = wc_price($default_price_month * 4);
            $price_text = "${cost} / year";
        } else {
            $cost = wc_price($default_price_year * (int) $size);
            $price_text = "${cost} / year";
        }
    }

    return $price_text;
}

/**
 * @param string|string[] $templates Possible template files
 * @return array
 */
function filter_templates($templates)
{
    $paths = apply_filters('sage/filter_templates/paths', [
        'views',
        'resources/views'
    ]);
    $paths_pattern = "#^(" . implode('|', $paths) . ")/#";

    return collect($templates)
        ->map(function ($template) use ($paths_pattern) {
            /** Remove .blade.php/.blade/.php from template names */
            $template = preg_replace('#\.(blade\.?)?(php)?$#', '', ltrim($template));

            /** Remove partial $paths from the beginning of template names */
            if (strpos($template, '/')) {
                $template = preg_replace($paths_pattern, '', $template);
            }

            return $template;
        })
        ->flatMap(function ($template) use ($paths) {
            return collect($paths)
                ->flatMap(function ($path) use ($template) {
                    return [
                        "{$path}/{$template}.blade.php",
                        "{$path}/{$template}.php",
                    ];
                })
                ->concat([
                    "{$template}.blade.php",
                    "{$template}.php",
                ]);
        })
        ->filter()
        ->unique()
        ->all();
}

/**
 * @param string|string[] $templates Relative path to possible template files
 * @return string Location of the template
 */
function locate_template($templates)
{
    return \locate_template(filter_templates($templates));
}

/**
 * Determine whether to show the sidebar
 * @return bool
 */
function display_sidebar()
{
    static $display;
    isset($display) || $display = apply_filters('sage/display_sidebar', false);
    return $display;
}

function add_fit_finder_kit($product, $sub_package)
{
    WC()->cart->add_to_cart($product, $sub_package->get_data()['attributes']['size']);
}

function customer_has_payment_card()
{

    $return = false;

    if (is_user_logged_in()) {
        $user = wp_get_current_user();
        $user_payment_method = \WC_Payment_Tokens::get_customer_default_token($user->ID);
        if ($user_payment_method) {
            $return = true;
        } else {
            $return = false;
        }
    } else {
        $return =  true;
    }

    return $return;
}

/**
 * @param \WC_Product|int $product
 * @return bool
 */
function is_in_stock($product)
{
    if (is_int($product)) {
        /** @var \WC_Product $product */
        $product = wc_get_product($product);
    }
    return $product->is_in_stock();
}

/**
 * @param \WC_Order $parent_order
 * @param \WC_Product $product
 * @param bool $sms_optin
 * @return int
 * @throws \WC_Data_Exception
 * @throws \Exception
 */
function create_related_order_for_size_kit($parent_order, $product, $sms_optin)
{
    $related_order = new \WC_Order();


    $related_order->add_product($product);

    $related_order->set_billing_address_1($parent_order->get_billing_address_1());
    $related_order->set_billing_address_2($parent_order->get_billing_address_2());
    $related_order->set_billing_city($parent_order->get_billing_city());
    $related_order->set_billing_company($parent_order->get_billing_company());
    $related_order->set_billing_country($parent_order->get_billing_country());
    $related_order->set_billing_first_name($parent_order->get_billing_first_name());
    $related_order->set_billing_last_name($parent_order->get_billing_last_name());
    $related_order->set_billing_last_name($parent_order->get_billing_last_name());

    $related_order->set_shipping_address_1($parent_order->get_shipping_address_1());
    $related_order->set_shipping_address_2($parent_order->get_shipping_address_2());
    $related_order->set_shipping_city($parent_order->get_shipping_city());
    $related_order->set_shipping_company($parent_order->get_shipping_company());
    $related_order->set_shipping_country($parent_order->get_shipping_country());
    $related_order->set_shipping_first_name($parent_order->get_shipping_first_name());
    $related_order->set_shipping_last_name($parent_order->get_shipping_last_name());
    $related_order->set_shipping_last_name($parent_order->get_shipping_last_name());

    $related_order->set_customer_id($parent_order->get_customer_id());
    $related_order->set_billing_phone($parent_order->get_billing_phone());
    $related_order->set_parent_id($parent_order->get_id());
    $related_order->set_status('processing');


    if ($sms_optin) {
        $related_order->update_meta_data('_wc_twilio_sms_optin', 1);
    }

    $related_order->save();

    foreach ($related_order->get_items() as $item) {
        wc_update_order_item_meta($item->get_id(), '_size_kit_order_related', $parent_order->get_id());
    }

    return $related_order->get_id();
}

function get_children_orders($order)
{
    $query = new \WC_Order_Query(array(
        'limit' => 1,
        'parent' => $order->get_id(),
        'customer_id' => get_current_user_id(),
    ));

    return $query->get_orders();
}

function get_subscription_details_order($order)
{
    $items = $order->get_items();
    $return = array();
    foreach ($items as $item) {
        if (\WC_Subscriptions_Product::is_subscription($item->get_data()['variation_id'])) {
            $related_subscription_orders = wcs_get_subscriptions_for_order($order, array('order_type' => array('parent', 'renewal')));
            $fresh_routine_refills = array_shift($related_subscription_orders);
            if ($fresh_routine_refills) {
                $variations = wc_get_product_variation_attributes($item->get_data()['variation_id']);
                $size_package = (int) $variations['attribute_size'];

                $return['id'] = $fresh_routine_refills->get_id();
                $return['size_package'] = $size_package;
            }
        }
    }

    return $return;
}

/**
 * @param \WC_Order $order
 */
function maybe_update_mouth_sizes($order)
{
    return $order->get_meta('_mouth_sizes_completed', true);
}


function next_delivery_for_annual_plan($now, $deliveries, $subscription,  $human = true)
{

    if (!is_array($deliveries))
        return '-';

    if ($subscription->get_status() === 'pending' || $subscription->get_status() === 'expired') {
        return '-';
    }

    $match = null;

    foreach ($deliveries as $delivery) {
        if ($now >= $delivery) {
            $match = $delivery;
            break;
        }
    }


    if ($match === null) {
        return $subscription->get_date_to_display('next_payment');
    }

    if ($human) {
        return date_i18n(get_option('date_format'), $match);
    }

    return $match;
}

function maybe_delivery_acive(\WC_Subscription $subscription)
{
    if ($subscription->get_status() === 'cancelled' && $subscription->get_billing_period() === 'year') {
        return 'still--active';
    }
}

/**
 * @param $new_date
 * @param $subscription_id
 */
function edit_delivery_date($new_date, $subscription_id)
{
    /** @var \WC_Subscription $subscription */
    $subscription = wcs_get_subscription($subscription_id);
    $sub_data = $subscription->get_data();

    if ($subscription->get_status() === 'active' && $sub_data['billing_period'] === 'year') {
        $deliveries = [];
        $deliveries_saved = $subscription->get_meta('_next_deliveries');

        foreach ($deliveries_saved as $cron_id => $date) {
            \ActionScheduler_Store::instance()->cancel_action($cron_id);
        }

        $subscription->delete_meta_data('_next_deliveries');

        for ($i = 0; $i < 3; $i++) {
            $next_shipping_date_in_time = strtotime('+' . 3 * $i . 'months', strtotime($new_date));
            $job_id = as_schedule_single_action($next_shipping_date_in_time, 'willo_scheduled_shipping_cycle', array('subscription_id' => (int) $subscription_id));
            $deliveries[$job_id] = $next_shipping_date_in_time;
        }

        $subscription->add_meta_data('_next_deliveries', $deliveries);

        $new_subcription_next_payment = strtotime('+9 months', strtotime($new_date));
        $dates = array();
        $dates['next_payment'] = date('Y-m-d H:i:s', $new_subcription_next_payment);

        $subscription->update_dates($dates);
    } elseif ($subscription->get_status() === 'active'  && $sub_data['billing_period'] === 'month') {
        $new_subcription_next_payment = strtotime($new_date);
        $dates = array();
        $dates['next_payment'] = date('Y-m-d H:i:s', $new_subcription_next_payment);
        $subscription->update_dates($dates);
    }



    return $subscription->save();
}

function schedule_reminder_and_refund(\WC_Order $order)
{
    $now = time();
    $reminders = [
        '1' => strtotime('+1 day', $now),
        '3' => strtotime('+3 days', $now),
        '7' => strtotime('+7 days', $now),
        '14' => strtotime('+14 days', $now),
        '21' => strtotime('+21 days', $now),
        '28' => strtotime('+28 days', $now)
    ];

    foreach ($reminders as $day => $reminder_time) {
        /** Cron reminder email mouthsizes */
        $job_id_reminder_email = as_schedule_single_action($reminder_time, "willo_scheduled_reminder_mouthsizes", array('order_id' => $order->get_id(), 'day' => $day));
        $order->add_meta_data("_cron_scheduled_reminder_mouthsizes_${day}", $job_id_reminder_email);
    }

    /** Cron full refund (30 days) */
    $job_id_full_refund = as_schedule_single_action(strtotime('+30 days', $now), 'willo_scheduled_full_refund_order', array('order_id' => $order->get_id()));
    $order->add_meta_data('_cron_scheduled_full_refund_order', $job_id_full_refund);

    $order->save();
}

function willo_label_order_status($status)
{
    switch ($status) {
        case 'completed':
        case 'processing':
            return 'Ordered';
        case 'out-delivery':
            return 'Out for delivery';
        case 'shipped':
            return 'Shipped';
        case 'delivered':
            return 'Delivered';
        default:
            return $status;
    }
}

function get_total_product_cart()
{
    $cart = WC()->cart;
    $count = 0;
    foreach ($cart->get_cart_contents() as $item) {
        if (\WC_Subscriptions_Product::is_subscription($item['variation_id'])) {
            $variation_object = wc_get_product_variation_attributes($item['variation_id']);
            $count +=  $variation_object['attribute_size'];
        } else {
            $count += (int) $item['quantity'];
        }
    }
    return $count;
}


function order_only_contains_fit_finder_kit(\WC_Order $order)
{

    if (count($order->get_items()) > 1) {
        return false;
    }

    foreach ($order->get_items() as $item) {
        /** @var \WC_Product $product */
        $product = wc_get_product($item->get_data()['product_id']);
        if ('yes' ===  $product->get_meta('_fit_finder_kit')) {
            return true;
        }
    }
}

function order_has_awating_refill(\WC_Order $order)
{
    foreach ($order->get_items() as $item) {
        /** @var \WC_Product $product */
        if (is_subscription($item->get_data()['variation_id'])) {
            return $item->get_data()['variation_id'];
        }
    }
    return null;
}

function find_variation_id_by_attr_value(string $attr_value, array $variations)
{
    foreach ($variations as $variation) {
        if (in_array($attr_value, $variation['attributes'], true)) {
            return $variation['variation_id'];
        }
    }
}
