<?php
/**
 * Email Styles
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-styles.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates/Emails
 * @version 4.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

// Load colors.
$bg        = get_option( 'woocommerce_email_background_color' );
$body      = get_option( 'woocommerce_email_body_background_color' );
$base      = get_option( 'woocommerce_email_base_color' );
$base_text = wc_light_or_dark( $base, '#202020', '#ffffff' );
$text      = get_option( 'woocommerce_email_text_color' );

// Pick a contrasting color for links.
$link_color = wc_hex_is_light( $base ) ? $base : $base_text;

if ( wc_hex_is_light( $body ) ) {
  $link_color = wc_hex_is_light( $base ) ? $base_text : $base;
}

$bg_darker_10    = wc_hex_darker( $bg, 10 );
$body_darker_10  = wc_hex_darker( $body, 10 );
$base_lighter_20 = wc_hex_lighter( $base, 20 );
$base_lighter_40 = wc_hex_lighter( $base, 40 );
$text_lighter_20 = wc_hex_lighter( $text, 20 );
$text_lighter_40 = wc_hex_lighter( $text, 40 );

// !important; is a gmail hack to prevent styles being stripped if it doesn't like something.
// body{padding: 0;} ensures proper scale/positioning of the email in the iOS native email app.
?>
body {
padding: 0;
}

hr {
max-width: 500px;
border-color: #001acf;
}

#wrapper {
background-color: <?php echo esc_attr( $bg ); ?>;
margin: 0;
padding:  0;
-webkit-text-size-adjust: none !important;
width: 100%;
}


#template_header, #template_footer {
background-color: <?php echo esc_attr( $base ); ?>;
color: <?php echo esc_attr( $base_text ); ?>;
border-bottom: 0;
font-weight: bold;
line-height: 100%;
vertical-align: middle;
font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
}

#template_header h1,
#template_header h1 a {
color: #142cd2;
}
#template_header_image {
width: 600px;
background:#fff0e4;
}
#template_header_image p {
  margin: 0;
  padding: 40px 0 0;
}
#template_header_image img {
margin-left: 0;
margin-right: 0;
max-width: 100px;
}

#template_footer td {
padding: 0;
}

#template_footer #credit {
border: 0;
color: <?php echo esc_attr( $text_lighter_40 ); ?>;
font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
font-size: 12px;
line-height: 150%;
text-align: center;
padding: 24px 0 0;
}

#template_footer #credit p {
margin: 0 0 16px;
}

#body_content {
background-color: <?php echo esc_attr( $body ); ?>;
}


tbody tr:first-child .td {
padding-top: 24px
}

tbody tr:last-child .td {
padding-bottom: 24px;
}

tfoot tr:first-child .td {
padding-top: 24px
}

.bottom-spaced .td{
padding-bottom: 24px;
}

tfoot tr:last-child .td {
padding-bottom: 20px;
padding-top: 20px;
border-top: 1px solid #e8e8e8;
font-weight:bold;
}

#body_content td ul.wc-item-meta {
font-size: small;
margin: 1em 0 0;
padding: 0;
list-style: none;
}

small {
color:#8e8e8e
}

#body_content td ul.wc-item-meta li {
margin: 0.5em 0 0;
padding: 0;
}

#body_content td ul.wc-item-meta li p {
margin: 0;
}

#body_content p {
margin: 0 0 16px;
}

#body_content_inner {
color: <?php echo esc_attr( $text_lighter_20 ); ?>;
font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
font-size: 14px;
line-height: 150%;
text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>;
}

.td {
color: <?php echo esc_attr( $text_lighter_20 ); ?>;
vertical-align: middle;
}

.address {
padding: 12px;
color: <?php echo esc_attr( $text_lighter_20 ); ?>;
}

.text {
color: <?php echo esc_attr( $text ); ?>;
font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
}

.link {
color: <?php echo esc_attr( $base ); ?>;
}

#header_wrapper {
padding: 22px;
display: block;
}

#order-id {
font-weight: normal;
font-size: 18px;
}

.email-btn {
  width: 80%;
  background: #142cd2;
  display: block;
  padding: 15px;
  border-radius: 33px;
  margin: 0 auto;
  text-align: center;
  text-decoration: none;
  color: #fff !important;
  text-transform: uppercase;
  font-weight: bold;
  font-size: 16px;
  letter-spacing: 1px;
  margin-top: 30px;
}

#header_wrapper h1{
font-weight:bold;
font-size: 36px;
line-height:40px
}

@media only screen and (min-width:480px) {
.mj-column-px-200 {
width: 250px !important;
max-width: 250px;
}
}

h1 {
  color: #001acf;
  font-weight:bold;
  font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
  text-align: center;
  font-size: 20px;
  font-weight: 300;
  line-height: 150%;
  margin: 0;
  padding: 16px 0;
  font-weight:bold;
}

h2 {
color: #001acf;
display: block;
font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
font-size: 18px;
font-weight: bold;
line-height: 130%;
margin: 0 0 18px;
text-align: center;

}

h3 {
color: <?php echo esc_attr( $base ); ?>;
display: block;
font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
font-size: 16px;
font-weight: bold;
line-height: 130%;
margin: 16px 0 8px;
text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>;
}

a {
color: #142cd2 !important;
font-weight: normal;
text-decoration: underline;
}

.order_item img {
background: #fff0e4;
border-radius: 50%;
}

.order_item:first-child td {
  border-top: 1px solid #e8e8e8;
}

.order_item:last-child td {
border-bottom: 1px solid #e8e8e8;
}

tfoot th {
font-weight: normal;
}

tfoot td {
  text-align:right !important;
}

.order_item img {
border: none;
display: inline-block;
font-size: 14px;
font-weight: bold;
height: auto;
outline: none;
text-decoration: none;
text-transform: capitalize;
vertical-align: middle;
width: 60px;
height: 60px;
margin-right: 8px;
}

.amount {
text-align: right;
display:block
}

.order_item td {
  font-weight:normal;
  font-size:14px
}

.willo_product_name_email {
display:inline-block;
vertical-align:middle;
line-height:22px
}

p {
  font-size:14px;
  line-height: 22px;
  text-align: center;
  color: #00315e;
font-weight:normal;
}

<?php
