<?php

/**
 * Register a custom menu page.
 */
function wpdocs_register_my_custom_menu_page(){
    add_menu_page(
        __( 'Preview emails', 'textdomain' ),
        'Preview emails',
        'manage_options',
        'custompage',
        'willo_preview_emails',
        'dashicons-visibility',
        100
    );
}
add_action( 'admin_menu', 'wpdocs_register_my_custom_menu_page' );

/**
 * Display a custom menu page
 */
function willo_preview_emails(){
    $query = new WC_Order_Query( array(
        'limit' => 15,
        'orderby' => 'date',
        'order' => 'DESC',
        'type' => 'shop_order'
    ) );

    $orders = $query->get_orders();
    ?>
    <div class="wrap">
        <form action="<?php echo admin_url('admin.php'); ?>" method="GET" id="willo-options-email">
            <input type="hidden" name="page" value="custompage">
            <div class="row">
                <label for="order">Select an order</label>
                <select name="order" id="order">
                    <?php foreach ($orders as $order): ?>
                        <option <?php selected($_GET['order'],$order->get_id(), true) ?> value="<?= $order->get_id() ?>">Order #<?= $order->get_id() ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="row">
                <label for="order">Email</label>
                <select name="status" id="order">
                    <option  <?php selected($_GET['status'], 'reset-password', true) ?> value="reset-password">Reset password</option>
                    <option  <?php selected($_GET['status'], 'processing', true) ?> value="processing">Processing</option>
                    <option  <?php selected($_GET['status'], 'shipped', true) ?> value="shipped">Shipped</option>
                    <option  <?php selected($_GET['status'], 'out-delivery', true) ?> value="out-delivery">Out for delivery</option>
                    <option  <?php selected($_GET['status'], 'delivered', true) ?> value="delivered">Delivered</option>
                </select>
            </div>
            <button class="button" type="submit">Preview</button>
        </form>
        <?php
            if( isset($_GET['order']) && isset($_GET['status']) ) :
                $email = new WC_Email();
                ob_start();
                extract($_GET);
                include get_template_directory() . '/email-preview.php';
                $content = ob_get_contents();
                $message = apply_filters( 'woocommerce_mail_content', $email->style_inline(  $content ) );
                ob_end_clean();
                echo $message;
        endif ?>
    </div>
    <style>
        #msg-config_warning, .notice, .error {display: none}
        #willo-options-email{
            display: flex;
            justify-content: center;
            padding: 12px;
            background: #fff;
            border-bottom: 4px solid #007cba;
            margin-bottom: 12px;
        }
        #willo-options-email .row label {
            text-transform: uppercase;
            font-weight: bold;
        }
        #willo-options-email .row{
            margin-right: 12px;
        }
    </style>
    <?php
}


