<?php
namespace App\Wc\Emails;

class WC_Shipped_Order_Email extends \WC_Email {
    public function __construct() {

        // set ID, this simply needs to be a unique name
        $this->id = 'wc_shipped_order';

        // this is the title in WooCommerce Email settings
        $this->title = 'Shipped Order';

        // this is the description in WooCommerce email settings
        $this->description = 'Shipped Order Notification emails are sent when the status order set to shipped';

        // these are the default heading and subject lines that can be overridden using the settings
        $this->heading = 'Shipped Order';
        $this->subject = get_field('shipped_email_subject','option');


        // these define the locations of the templates that this email should use, we'll just use the new order template since this email is similar
        $this->template_html  = 'emails/shipped-email-notification.blade.php';

        // Trigger on new paid orders
        add_action( 'woocommerce_order_status_shipped', array( $this, 'trigger' ) );

        // Call parent constructor to load any other defaults not explicity defined here
        parent::__construct();

        // this sets the recipient to the settings defined below in init_form_fields()
        $this->recipient = $this->get_option( 'recipient' );

        // if none was entered, just use the WP admin email as a fallback
        if ( ! $this->recipient ) {
            $this->recipient = 'Customer';

        }
    }

    public function get_theme_template_file( $template ) {
        return get_stylesheet_directory() . '/views/' . apply_filters( 'woocommerce_template_directory', 'woocommerce', $template ) . '/' . $template;
    }

    public function trigger( $order_id ) {

        // bail if no order ID is present
        if ( ! $order_id ) {
            return;
        }


        $order = wc_get_order( $order_id );


        if ( is_a( $order, 'WC_Order' ) ) {
            $this->object                         = $order;
            $this->recipient                      = $this->object->get_billing_email();
        }

        if ( $this->is_enabled() && $this->get_recipient() ) {
            $this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
        }

        $this->restore_locale();

        // woohoo, send the email!
    }

    public function get_content_html() {
        return wc_get_template_html(
            $this->template_html,
            array(
                'order'              => $this->object,
                'email_heading'      => $this->get_heading(),
                'additional_content' => $this->get_additional_content(),
                'sent_to_admin'      => false,
                'plain_text'         => false,
                'email'              => $this,
            )
        );
    }
}
