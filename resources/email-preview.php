<?php

use function App\template;

$order = wc_get_order($_GET['order']);
$order->set_status( $_GET['status'] );
switch ($_GET['status']) {
    case 'processing':
        echo template('woocommerce.emails.customer-processing-order', ['order' => $order]);
        break;
    case 'shipped':
        echo template('woocommerce.emails.shipped-email-notification', ['order' => $order]);
        break;
    case 'out-delivery':
        echo template('woocommerce.emails.out-for-delivery-email-notification', ['order' => $order]);
        break;
    case 'delivered':
        echo template('woocommerce.emails.delivered-email-notification', ['order' => $order]);
        break;
    case 'reset-password':
        echo template('woocommerce.emails.customer-reset-password', ['order' => $order]);
        break;
    default:
        break;
}
