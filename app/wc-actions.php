<?php

use App\Klaviyo;
use function App\create_related_order_for_size_kit;
use function App\edit_delivery_date;
use function App\is_in_stock;
use function App\sage;
use function App\schedule_reminder_and_refund;
use \Siro\Klaviyo\KlaviyoAPI;


remove_action('woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20);
remove_action('woocommerce_review_order_after_order_total', WC_Subscriptions_Cart::class . '::display_recurring_totals');
remove_action('woocommerce_email_after_order_table', WC_Subscriptions_Order::class . '::add_sub_info_email', 15);
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

add_action('email_later_subscription_review', function ($order) {
    if (wcs_order_contains_subscription($order)) {
        return;
    } elseif (wcs_order_contains_subscription($order, 'renewal') && $order->get_total()  > 0) {
        /** @var WC_Subscription $sub */
        $sub = array_first(wcs_get_subscriptions_for_order($order, array(
            'order_type' => 'renewal'
        )));
        $subscription = \App\order_has_awating_refill(wc_get_order($sub->get_id()));
        $_product = wc_get_product($subscription);
        $variation_object = wc_get_product_variation_attributes($_product->get_id());
?>
        <tr class="order_item">
            <td class="product-name td" colspan="4" style="display: table-cell;border-top: 1px solid #e8e8e8;padding-top: 24px">
                <div style="display: flex;line-height: 25px;">
                    <span class="product-name__image">
                        <?php
                        echo $_product->get_image(); // PHPCS: XSS ok.
                        ?>
                    </span>
                    <span class="product-name__review-text">
                        <?php
                        echo sprintf(__('%d %s'), $variation_object['attribute_size'], $_product->get_name())
                        ?>
                        <div class="product-name__price" style="display: flex;
   width: 100%;">
                            <div style="display: flex;">
                                <?php
                                echo sprintf(
                                    __(
                                        '<span class="price" style="margin-right: 4px;text-align: left;display: flex;">%s %s</span><span style="text-align: left;display: flex;text-decoration: line-through;" class="intitial_price">%s</span>'
                                    ),
                                    wc_price($_product->get_price()),
                                    $variation_object['attribute_billing-period'] === 'Year' ? '/year' : ' every 3 months',
                                    \App\calc_percentage_saved($_product->get_id())
                                );
                                ?>
                            </div>
                        </div>

                    </span>
                </div>
            </td>
        </tr>
        <?php
    } else {
        if (!\App\order_only_contains_fit_finder_kit($order)) {
            $order_has_awaiting_refill = \App\order_has_awating_refill($order);
            session_start();
            /** @var WC_Product $subscription */
            if ($order_has_awaiting_refill !== null) {
                $_product = wc_get_product($order_has_awaiting_refill);
                $variation_object = wc_get_product_variation_attributes($_product->get_id());
            } else {
                if (empty($_SESSION['later_subscription']))
                    return;

                $_product = wc_get_product($_SESSION['later_subscription']);
                $variation_object = wc_get_product_variation_attributes($_product->get_id());
            }

        ?>
            <tr class="order_item">
                <td class="product-name td" colspan="4" style="display: table-cell;border-top: 1px solid #e8e8e8;padding-top: 24px">
                    <div style="display: flex;line-height: 25px;">
                        <span class="product-name__image">
                            <?php
                            echo $_product->get_image(); // PHPCS: XSS ok.
                            ?>
                        </span>
                        <span class="product-name__review-text">
                            <?php
                            echo sprintf(__('%d %s'), $variation_object['attribute_size'], $_product->get_name())
                            ?>
                            <div class="product-name__price" style="display: flex;
   width: 100%;">
                                <div style="display: flex;">
                                    <?php
                                    echo sprintf(
                                        __(
                                            '<span class="price" style="margin-right: 4px;text-align: left;display: flex;">%s %s</span><span style="text-align: left;display: flex;text-decoration: line-through;" class="intitial_price">%s</span>'
                                        ),
                                        wc_price($_product->get_price()),
                                        $variation_object['attribute_billing-period'] === 'Year' ? '/year' : ' every 3 months',
                                        \App\calc_percentage_saved($_product->get_id())
                                    );
                                    ?>
                                </div>
                            </div>
                            <small>You will be charged later (once Willo ships)</small>

                        </span>
                    </div>
                </td>
            </tr>
    <?php
        }
    }
});

add_action('woocommerce_review_order_after_cart_contents', function () {
    session_start();
    /** @var WC_Product $subscription */
    $_product = wc_get_product($_SESSION['later_subscription']);
    $variation_object = wc_get_product_variation_attributes($_product->get_id());
    ?>
    <tr id="side-product">
        <td class="product-name">
            <div class="message">Paying later</div>
            <span class="product-name__image">
                <?php
                echo $_product->get_image(); // PHPCS: XSS ok.
                ?>
            </span>
            <span class="product-name__review-text">
                <?php
                echo sprintf(__('%d %s'), $variation_object['attribute_size'], $_product->get_name())
                ?>
                <span>
                    <?php
                    echo apply_filters('willo_sumary_detail_product', get_field('product_order_summary_details', $_product->get_id()), $_product)
                    ?>
                </span>
                <div class="product-name__price inline">
                    <div>
                        <?php
                        echo sprintf(
                            __(
                                '<span class="price">%s%s</span><span class="intitial_price" style="text-decoration: line-through;">%s</span>'
                            ),
                            wc_price($_product->get_price()),
                            $variation_object['attribute_billing-period'] === 'Year' ? '/year' : ' every 3 months',
                            \App\calc_percentage_saved($_product->get_id())
                        );
                        ?>
                    </div><?php
                            do_action('willo_cart_item_product_total', $_product);
                            ?>
                </div>
                <small>You will be charged later (once Willo ships)</small>

            </span>
        </td>
    </tr>
<?php

});

add_action('woocommerce_before_add_to_cart_button', 'plugin_republic_add_text_field');
function plugin_republic_add_text_field($pid)
{ ?>
    <div class="pr-field-wrap">
        <input type="hidden" name='subscription-id' id='later-subscription' value='<?= $pid ?>'>
    </div>
    <?php }

add_action('woocommerce_before_order_itemmeta', function ($item_id, $item, $product) {
    if (\App\is_subscription($product)) {
        $attributes = $product->get_data()['attributes']
    ?>
        <div class="willo-plan-size-package">
            fresh routine plan :
            <?php
            if ($attributes['size'] === '1') {
                echo '<strong>SOLO</strong>';
            } elseif ($attributes['size'] === '2') {
                echo '<strong>DUO</strong>';
            } else {
                echo '<strong>FAMILY OF ' . $attributes['size'] . '</strong>';
            }
            ?>
        </div>
    <?php
    }
}, 10, 3);

add_action('willo-checkout-payment', 'woocommerce_checkout_payment');

add_action('woocommerce_after_checkout_billing_form', function () {
    ?>
    <button style="display:none;float: right;margin-bottom: 40px;margin-top: 40px" type="button" class="btn btn--blue checkout-billing-continue next-steps">Continue</button>
    <?php
});

add_action('woocommerce_payment_complete', 'payment_complete');
function payment_complete($order_id)
{
    session_start();
    /** @var WC_Order $order */
    $order = wc_get_order($order_id);
    $payment_method = $order->get_payment_method();
    $order_items = $order->get_items();
    /** @var WP_User $user */
    $user = $order->get_user();
    $mouth_sizes = [];
    $sms_optin = false;
    $subscription_checkout = wc_get_product($_SESSION['later_subscription']);

    if (!empty($_POST['wc_twilio_sms_optin'])) {
        $sms_optin = true;
    }

    foreach ($order_items as $item) {
        if (
            $item->get_data()['product_id'] === sage('config')->get('theme')['side_product_add_cart']['fit_finder_kit']
        ) {
            try {
                create_related_order_for_size_kit(
                    $order,
                    wc_get_product($item->get_data()['product_id']),
                    $sms_optin
                );
                $order->remove_item($item->get_id());
            } catch (WC_Data_Exception $e) {
                die($e);
            }
        }
    }

    if (\App\is_subscription($subscription_checkout) && !wcs_order_contains_renewal($order)) {
        $variations = wc_get_product_variation_attributes($subscription_checkout->get_id());
        $size_package = (int) $variations['attribute_size'];
        for ($i = 1; $i <= $size_package; $i++) {
            $mouth_sizes[] = array(
                'name' => null,
                'size' => null
            );
        }

        $order->update_meta_data('_mouth_sizes', $mouth_sizes);
        $order->update_meta_data('_mouth_sizes_completed', false);
        $order->add_product($subscription_checkout);
    }

    /** Allow willo to hook at this point */
    do_action("willo_{$payment_method}_completed", $order, $user);
    $order->save();
}

add_action('woocommerce_subscription_payment_complete', function ($subscription) {
    $next_payment = strtotime($subscription->get_date_to_display('next_payment'));
    $sub_data = $subscription->get_data();
    if ($subscription->get_status() === 'active' && $sub_data['billing_period'] === 'year') {
        /** We split delivery inside 3 months */
        $deliveries = [];
        for ($i = 1; $i <= 3; $i++) {
            $next_shipping_date_in_time =   strtotime('-' . 3 * $i . 'months', $next_payment);
            $job_id = as_schedule_single_action($next_shipping_date_in_time, 'willo_scheduled_shipping_cycle', array('subscription_id' => $subscription->get_id()));
            $deliveries[$job_id] = $next_shipping_date_in_time;
        }
        $subscription->add_meta_data('_next_deliveries', $deliveries);
    }

    $subscription->save();
}, 10, 2);

add_action('woocommerce_order_status_changed', function ($order_id, $old_status, $new_status) {
    if ($new_status === 'delivered') {
        /** @var WC_Order $order */
        $order = wc_get_order($order_id);
        if (!$order->get_meta('reminders_email_defined')) {
            $items = $order->get_items();
            foreach ($items as $item) {
                $product_id = $item->get_variation_id() ? $item->get_variation_id() : $item->get_product_id();
                if ($product_id === sage('config')->get('theme')['side_product_add_cart']['fit_finder_kit']) {
                    schedule_reminder_and_refund(wc_get_order($order->get_parent_id()));
                }
            }
            $order->update_meta_data('reminders_email_defined', true);
            $order->save();
        }
    }

    if ($new_status === 'shipped') {

        /** @var WC_Order $order */
        $order = wc_get_order($order_id);

        /** On considère que les tailles ne sont pas determiné */
        if ($old_status !== 'completed') {
            return;
        }


        $items = $order->get_items();
        foreach ($items as $item) {
            $product_id = $item->get_variation_id() ? $item->get_variation_id() : $item->get_product_id();

            if (\App\is_subscription($product_id)) {
                /** @var WC_Product_Subscription_Variation $product */
                $product = wc_get_product($product_id);

                $interval = $product->get_variation_attributes()['attribute_billing-period'] === 'Year' ? 1 : 3;
                $period = $product->get_variation_attributes()['attribute_billing-period'] === 'Year' ? 'year' : 'month';

                /** @var WC_Subscription $sub */
                $sub = wcs_create_subscription(array(
                    'order_id' => $order->get_id(),
                    'billing_period' => $period,
                    'billing_interval' => $interval,
                    'customer_id' => $order->get_user()->ID
                ));

                /** Billing address */
                $sub->set_billing_first_name($order->get_billing_first_name());
                $sub->set_billing_last_name($order->get_billing_last_name());
                $sub->set_billing_address_1($order->get_billing_address_1());
                $sub->set_billing_country($order->get_billing_country());
                $sub->set_billing_city($order->get_billing_city());
                $sub->set_billing_postcode($order->get_billing_postcode());
                $sub->set_billing_address_2($order->get_billing_address_2());
                $sub->set_billing_email($order->get_billing_email());
                $sub->set_billing_phone($order->get_billing_phone());

                /** Shipping address */
                $sub->set_shipping_first_name($order->get_shipping_first_name());
                $sub->set_billing_last_name($order->get_shipping_last_name());
                $sub->set_shipping_address_1($order->get_shipping_address_1());
                $sub->set_shipping_country($order->get_shipping_country());
                $sub->set_shipping_city($order->get_shipping_city());
                $sub->set_shipping_postcode($order->get_shipping_postcode());
                $sub->set_shipping_address_2($order->get_shipping_address_2());

                $sub->set_payment_method(
                    $order->get_payment_method()
                );

                $sub->add_product($product);

                $sub->calculate_totals();

                do_action('willo_process_subscription_delivery', $sub, $order);

                WC_Subscriptions_Manager::activate_subscriptions_for_order($order);

                do_action('woocommerce_scheduled_subscription_payment', $sub->get_id());
            }
        }
    }

    /** @var WC_Order $order */
    $order = wc_get_order($order_id);

    if (wcs_order_contains_renewal($order)) {
        var_dump(wcs_order_contains_subscription($order, 'renewal'));
        /** @var WC_Subscription $sub */

        $sub = array_first(wcs_get_subscriptions_for_order($order, array(
            'order_type' => 'renewal'
        )));


        if ($sub && !$order->get_meta('_free_delivery_for_annual_subscrition')) {
            $order->set_total($sub->get_data()['total']);
            $order->save();
        }
    }
}, 10, 3);

add_action('willo_process_subscription_delivery', function ($sub, $order) {
    $size_product = wc_get_product(get_field('fresh_routine_kit', 'option'));
    foreach ($order->get_meta('_mouth_sizes') as $size) {
        $variant = wc_get_product(\App\find_variation_id_by_attr_value($size['size'], $size_product->get_available_variations()));
        $sub->add_product($variant);
        $sub->save();
    }
}, 10, 2);

add_action('woocommerce_subscription_renewal_payment_complete', function ($subscription, $last_order) {
    if (!$subscription)
        return;

    foreach ($last_order->get_items() as $index => $item) {
        if (\App\is_subscription($item->get_product_id())) {
            $last_order->remove_item($item->get_id());
        }
    }

    $last_order->save();
}, 10, 2);

add_action('woocommerce_cart_updated', 'delete_persistent_cart');
function delete_persistent_cart()
{
    if (get_current_user_id()) {
        WC()->cart->persistent_cart_destroy();
    }
}

add_action('willo_cart_item_product_total', function ($product) {
    if (WC_Subscriptions_Product::is_subscription($product)) {
        $variations = wc_get_product_variation_attributes($product->get_id());
        $period = $variations['attribute_billing-period'];
        $sizes = $variations['attribute_billing-period'];
    ?>
        <div class="refill_qty_updater" data-min="1" data-max="5">
            <span class="refill_qty_updater__minus">-</span>
            <input data-period="<?= $period; ?>" class="refill_qty_updater__value" type="text" value="<?php echo $variations['attribute_size'] ?>">
            <span class="refill_qty_updater__plus">+</span>
        </div>
    <?php
    }
}, 10, 1);

add_action('template_redirect', function () {
    if (is_shop() || is_cart()) {
        wp_redirect(get_permalink(sage('config')->get('theme')['fresh_routine_parent_variation']), 301);
    }
});

add_action('willo_add_cart_btn', function ($product_id) {
    /** @var WC_Product $product */
    $product = wc_get_product($product_id);
    if (is_in_stock($product)) {
    ?>
        <p class="text--blue">100% Smiles Guaranteed. 30 Day Free Returns.</p>
        <div class="willo_product__submit">
            <button id="willo_product__add-to-cart" type="submit" class="btn btn--blue btn--full">
                <?php echo __('PAY TODAY <span id="add-cart-price"></span>'); ?>
            </button>
        </div>
        <p class="text--center">Shipping September 2020 — US only</p>
    <?php
    } else {
        echo \App\template('partials.waitlist-optin');
    }
}, 10, 1);

add_action('woocommerce_account_order-update-mouthpiece-size_endpoint', function ($order_id) {
    /** @var WC_Order $order */
    $order = wc_get_order($order_id);

    if ($order->get_user_id() !== get_current_user_id()) {
        wp_redirect(wc_get_account_endpoint_url('orders'));
    }

    echo \App\template('partials.woocommerce.endpoints.update-mouthpiece-size', array(
        'order_id' => $order_id,
        'order' => wc_get_order($order_id)
    ));
});

add_action('woocommerce_account_edit-subscription-plan_endpoint', function ($subscription) {
    echo \App\template('partials.woocommerce.endpoints.edit-subscription-plan', array(
        'subscription' => wcs_get_subscription($subscription),
    ));
});

add_action('woocommerce_account_modify-subscription-plan_endpoint', function ($subscription) {
    echo \App\template('partials.woocommerce.endpoints.modify-subscription-plan', array(
        'subscription' => wcs_get_subscription($subscription),
    ));
});

add_action('woocommerce_account_edit-address-billing_endpoint', function ($subscription) {
    echo \App\template('partials.woocommerce.endpoints.edit-user-billing-address', array(
        'subscription' => wcs_get_subscription($subscription),
    ));
});

add_action('woocommerce_account_edit-address-shipping_endpoint', function ($subscription) {
    echo \App\template('partials.woocommerce.endpoints.edit-user-shipping-address', array(
        'subscription' => wcs_get_subscription($subscription),
    ));
});

add_action('willo_scheduled_shipping_cycle', function ($subscription_id) {
    $process_shipping_order = apply_filters('fcds_wcs_process_delivery_order', true, $subscription_id);
    if ($process_shipping_order) {
        $order_note = __('Processing order for delivery', 'willo');
        $required_status = apply_filters('fcds_wcs_required_subscription_status_for_processing_delivery_order', array('active', 'pending-cancel'), $subscription_id);
        $renewal_order = processShippingOrder($subscription_id, $required_status, $order_note);

        // Backward compatibility with Subscriptions < 2.2.12 where we returned false for an unknown reason
        if (false === $renewal_order) {
            return $renewal_order;
        }
    }
});

function processShippingOrder($subscription_id, $required_status, $order_note)
{

    $subscription = wcs_get_subscription($subscription_id);

    // If the subscription is using manual payments, the gateway isn't active or it manages scheduled payments
    if (!empty($subscription) && $subscription->has_status($required_status)) {

        $subscription->add_order_note($order_note);

        remove_filter('wcs_renewal_order_created', array('WC_Subscriptions_Renewal_Order', 'add_order_note'), 10, 2);

        // Generate a renewal order for payment gateways to use to record the payment (and determine how much is due)
        $shipping_order = wcs_create_renewal_order($subscription);

        if (is_wp_error($shipping_order)) {
            // let's try this again
            $shipping_order = wcs_create_renewal_order($subscription);

            if (is_wp_error($shipping_order)) {
                throw new Exception(sprintf(__('Error: Unable to create delivery order with note "%s"', FCDS_F_WCS_TEXT_DOMAIN), $order_note));
            }
        }

        $order_number = sprintf(_x('#%s', 'hash before order number', FCDS_F_WCS_TEXT_DOMAIN), $shipping_order->get_order_number());

        // translators: placeholder is order ID
        $subscription->add_order_note(sprintf(__('Order %s created to record shipping.', FCDS_F_WCS_TEXT_DOMAIN), sprintf('<a href="%s">%s</a> ', esc_url(wcs_get_edit_post_link(wcs_get_objects_property($shipping_order, 'id'))), $order_number)));

        add_filter('wcs_renewal_order_created', array('WC_Subscriptions_Renewal_Order', 'add_order_note'), 10, 2);

        setOrderPriceAsZero($shipping_order);

        do_action('fcsc_wcs_before_change_shipping_order_status_to_complete', $shipping_order, $subscription);
        $change_order_status_to_complete = apply_filters('fcds_wcs_change_delivery_order_status_to_complete_on_process_delivery_cycles', true, $shipping_order);
        if ($change_order_status_to_complete) {
            $shipping_order->set_status(apply_filters('woocommerce_payment_complete_order_status', $shipping_order->needs_processing() ? 'processing' : 'completed', $shipping_order->get_id(), $shipping_order));
            $shipping_order->save();
        }

        //Send emails
        do_action('created_prepaid_shipping_order_email', $subscription, $shipping_order);
        do_action('created_prepaid_shipping_order_email_for_customer', $subscription, $shipping_order);

        do_action('fcds_wcs_after_change_delivery_order_status_to_complete', $shipping_order, $subscription);
    } else {
        $shipping_order = false;
    }

    return $shipping_order;
}

/**
 * @param WC_Order $order
 */
function setOrderPriceAsZero($order)
{
    $total = 0;
    //If we set false order item price is as original price.
    if ($order) {
        //For setting the item price as multiplied price based on recurring interval
        foreach ($order->get_items() as $item_id => $item) {
            // Set the new price
            $item->set_subtotal($total);
            $item->set_total($total);
            // Make new taxes calculations
            $item->calculate_taxes();

            if (\App\is_subscription($item->get_product_id())) {
                $order->remove_item($item->get_id());
            }

            $item->save(); // Save line item data
        }
    }

    $order->set_total($total);

    // UPM FIX, we enforce legacy total that is used in report pages
    $order->legacy_set_total($total, 'total');
    $order->legacy_set_total($total, 'tax');
    $order->legacy_set_total($total, 'shipping_tax');
    $order->legacy_set_total($total, 'shipping');
    $order->legacy_set_total($total, 'cart_discount_tax');
    $order->add_meta_data('_free_delivery_for_annual_subscrition', true);
    $order->save();
}

add_action('willo_user_successfuly_update_sizes', function ($order) {
    /** Remove reminder email and full refund cron */

    $reminders_mouth_piece = array('1', '3', '7', '14', '21', '28');

    foreach ($reminders_mouth_piece as $day) {
        if ($order->get_meta("_cron_scheduled_reminder_mouthsizes_${day}")) {
            \ActionScheduler_Store::instance()->cancel_action($order->get_meta("_cron_scheduled_reminder_mouthsizes_${day}"));
            $order->delete_meta_data("_cron_scheduled_reminder_mouthsizes_${day}");
        }
    }

    if ($order->get_meta('_cron_scheduled_full_refund_order')) {
        \ActionScheduler_Store::instance()->cancel_action($order->get_meta('_cron_scheduled_full_refund_order'));
        $order->delete_meta_data('_cron_scheduled_full_refund_order');
    }

    $order->save();
});

add_action('willo_scheduled_reminder_mouthsizes', function ($order_id, $day) {
    /** @var WC_Order $order */
    $order = wc_get_order($order_id);
    if ($order) {
        switch ($day) {
            case '1':
                Klaviyo::send_template(
                    'QQGa92',
                    'Remember to Find your Fit!',
                    [
                        array(
                            'email' => $order->get_billing_email(),
                            'name' => $order->get_billing_first_name()
                        )
                    ],
                    array(
                        'name' => 'Recipient'
                    )
                );
                break;
            case '3':
                Klaviyo::send_template(
                    'WTgzYv',
                    'Update Your Mouthpiece Size',
                    [
                        array(
                            'email' => $order->get_billing_email(),
                            'name' => $order->get_billing_first_name()
                        )
                    ],
                    array(
                        'name' => 'Recipient'
                    )
                );
                break;
            case '14':
            case '7':
            case '21':
                Klaviyo::send_template(
                    'T3rN7d',
                    'Update Your Willo Size',
                    [
                        array(
                            'email' => $order->get_billing_email(),
                            'name' => $order->get_billing_first_name()
                        )
                    ],
                    array(
                        'name' => 'Recipient'
                    )
                );
                break;
            case '28':
                Klaviyo::send_template(
                    'U6J7Xj',
                    'Final Reminder: Update Your Willo Size',
                    [
                        array(
                            'email' => $order->get_billing_email(),
                            'name' => $order->get_billing_first_name()
                        )
                    ],
                    array(
                        'name' => 'Recipient'
                    )
                );
                break;
            default:
                break;
        }
    }
}, 10, 2);

add_action('willo_scheduled_full_refund_order', function ($order_id) {

    $order  = wc_get_order($order_id);
    $subs_order = wcs_get_subscriptions_for_order($order);

    /** @var WC_Subscription $subscription */
    $subscription = array_shift($subs_order);

    if ($subscription) {
        /** Also expired subscription */
        $subscription->set_status('cancelled');
        $subscription->save();
    }

    // If it's something else such as a WC_Order_Refund, we don't want that.
    if (!is_a($order, 'WC_Order')) {
        return new WP_Error('wc-order', __('Provided ID is not a WC Order', 'yourtextdomain'));
    }

    if ('refunded' == $order->get_status()) {
        return new WP_Error('wc-order', __('Order has been already refunded', 'yourtextdomain'));
    }


    // Get Items
    $order_items   = $order->get_items();

    // Refund Amount
    $refund_amount = 0;

    // Prepare line items which we are refunding
    $line_items = array();

    if ($order_items) {
        foreach ($order_items as $item_id => $item) {
            if (!\App\is_subscription($item->get_product_id())) {
                $item_meta     = $order->get_item_meta($item_id);

                $tax_data = $item_meta['_line_tax_data'];

                $refund_tax = 0;

                if (is_array($tax_data[0])) {

                    $refund_tax = array_map('wc_format_decimal', $tax_data[0]);
                }

                $refund_amount = wc_format_decimal($refund_amount) + wc_format_decimal($item_meta['_line_total'][0]);

                $line_items[$item_id] = array(
                    'qty' => $item_meta['_qty'][0],
                    'refund_total' => wc_format_decimal($item_meta['_line_total'][0]),
                    'refund_tax' =>  $refund_tax
                );
            } else {
                $order->remove_item($item->get_id());
            }
        }
    }

    $refund = wc_create_refund(array(
        'amount'         => $refund_amount,
        'reason'         => __('Customer has not updated the sizes'),
        'order_id'       => $order_id,
        'line_items'     => $line_items,
        'refund_payment' => true
    ));

    $order->set_status('refunded');
    $order->save();

    return $refund;
});

/**
 * Display field value on the order edit page
 */

// Add Checkbox
add_action('woocommerce_review_order_before_order_total', 'coupon_code');
function coupon_code()
{
    ?>
    <tr>
        <th id="shipping" class="shipping">
            Shipping
        </th>
        <td class="">
            Free
        </td>
    </tr>
    <tr id="coupon">
        <th id="coupon-code" class="coupon-code">
            Enter promo code
        </th>
        <td class="action__enter-discout">
            <img src="<?php echo \App\asset_path('images/plus-icon.svg'); ?>" alt="">
            <span class="flex flex--a-center" id="discount_input_wrapper" style="display:none">
                <input type="text" id="discount_input" placeholder="Promo code">
                <a href="#" class="validate" style="margin-left: 8px">Apply</a>
            </span>
        </td>
    </tr>
<?php
}

add_action('woocommerce_subscription_status_cancelled', function (WC_Subscription $subscription) {
    /** @var WC_Order $order */
    $order = $subscription->get_parent();
    if ($order) {
        if ($order->get_meta('_cron_scheduled_reminder_mouthsizes')) {
            \ActionScheduler_Store::instance()->cancel_action($order->get_meta('_cron_scheduled_reminder_mouthsizes'));
            $order->delete_meta_data('_cron_scheduled_reminder_mouthsizes');
        }

        if ($order->get_meta('_cron_scheduled_full_refund_order')) {
            \ActionScheduler_Store::instance()->cancel_action($order->get_meta('_cron_scheduled_full_refund_order'));
            $order->delete_meta_data('_cron_scheduled_full_refund_order');
        }

        $order->save();
    }
});

add_action('willo_user_signup_successfully', function ($user, $subscribe) {

    Klaviyo::send_template(
        'UDfZsi',
        'New account on willo.com',
        [
            array(
                'email' =>  $user->user_email,
                'name' =>  $user->display_name
            )
        ],
        array(
            'name' => 'Recipient'
        )
    );

    if ($subscribe === 'true') {
        $email = $user->user_email;
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
                    '$country' => $result['country_name'],
                    '$region' => $result['region_name'],
                    '$city' => $result['city'],
                    '$timezone' => $result['time_zone']
                ])
            );

            if ($result[0]->id) {
                wp_send_json_success($result[0]->id);
            } else {
                wp_send_json_error();
            }
        }
    }
}, 10, 2);


function willo_cf7_optin_pro()
{
    $submission = WPCF7_Submission::get_instance();

    if ($submission) {

        // On récupère les données du formulaire
        $posted_data = $submission->get_posted_data();
        $newsletter_subscribe = $posted_data['newsletter-id'];
        $email = $posted_data['your-email'];

        if (!empty($newsletter_subscribe)) {
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
                    sage('config')->get('theme')['klaviyo']['newsletter_list_id_pro'],
                    array([
                        'email' => $email,
                        '$country' => $result['country_name'],
                        '$region' => $result['region_name'],
                        '$city' => $result['city'],
                        '$timezone' => $result['time_zone'],
                        'occupation' => $posted_data['occupation'],
                        'license_no' => $posted_data['license-number'],
                        'state' => $posted_data['state']
                    ])
                );
            }
        }
    }
}
add_action('wpcf7_mail_sent', 'willo_cf7_optin_pro');

add_action('template_redirect', function () {
    if (!\App\is_in_stock(App\sage('config')->get('theme')['side_product_add_cart']['essential_set'])) {
        setcookie("willo_pioneer_program_ok", '', 1, '/');
        WC()->cart->empty_cart();
    }
});

// Display Fields
add_action('woocommerce_product_options_general_product_data', 'woocommerce_product_custom_fields');
function woocommerce_product_custom_fields()
{
    global $woocommerce, $post;
    echo '<div class="product_custom_field">';
    // Custom Product Text Field
    woocommerce_wp_checkbox(
        array(
            'id' => '_fit_finder_kit',
            'placeholder' => 'Allow us to target the fit finder kit',
            'label' => __('Is it a fit finder kit product?', 'woocommerce'),
            'desc_tip' => 'true'
        )
    );
    echo '</div>';
}

// Save Fields
add_action('woocommerce_process_product_meta', 'woocommerce_product_custom_fields_save');
function woocommerce_product_custom_fields_save($post_id)
{
    // Custom Product Text Field
    $woocommerce_custom_product_fit_finder = $_POST['_fit_finder_kit'];
    if (!empty($woocommerce_custom_product_fit_finder)) {
        update_post_meta($post_id, '_fit_finder_kit', esc_attr($woocommerce_custom_product_fit_finder));
    }
}
