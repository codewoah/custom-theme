<?php
/**
 * Email Footer
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-footer.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates/Emails
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;
?>
</div>
</td>
</tr>
</table>
<!-- End Content -->
</td>
</tr>
</table>
<!-- End Body -->
</td>
</tr>
</table>
</td>
</tr>
<tr>
  <td align="center" valign="top">
    <!-- Footer -->
    <table border="0" cellpadding="10" cellspacing="0" id="template_footer" width="600">
      <tr>
        <td valign="top">
          <table border="0" cellpadding="10" cellspacing="0" width="100%">
            <tr>
              <td colspan="2" valign="middle" id="credit">
                <hr>
                <img style="margin-top:32px; margin-bottom: 24px" width="30" src="https://d3k81ch9hvuctc.cloudfront.net/company/H3gJ4u/images/201be278-cafe-42a3-ade1-56f1f5525d22.png" alt="">
                {!! get_field('willo_emails_footer_content','option') !!}
              </td>
            </tr>
            @include('woocommerce.emails.socials-footer')
          </table>
        </td>
      </tr>
    </table>
    <!-- End Footer -->
  </td>
</tr>
</table>
</div>
</body>
</html>
