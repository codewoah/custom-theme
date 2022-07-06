<h1>Customer information</h1>
<div style="">
  <!-- SECTION -->
  <!--[if mso | IE]>
  <table
    align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:600px;" width="600"
  >
    <tr>
      <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
  <![endif]-->
  <div style="margin:0px auto;max-width:600px;">
    <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
      <tbody>
      <tr>
        <td style="direction:ltr;font-size:0px;padding:20px 0;text-align:center;">
          <!--[if mso | IE]>
          <table role="presentation" border="0" cellpadding="0" cellspacing="0">

            <tr>

              <td
                class="" style="vertical-align:top;width:200px;"
              >
          <![endif]-->
          <div class="mj-column-px-200 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
            <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
              <tr>
                <td align="left" style="font-size:0px;padding:10px 25px;padding-top:O;padding-bottom:0;word-break:break-word;">
                  <div style="font-family:Ubuntu, Helvetica, Arial, sans-serif;font-size:13px;font-weight:bold;line-height:1;text-align:left;color:#00315e;">Shipped to</div>
                </td>
              </tr>
              <tr>
                <td align="left" style="font-size:0px;padding:10px 25px;padding-top:O;padding-bottom:0;word-break:break-word;">
                  <div style="font-family:Ubuntu, Helvetica, Arial, sans-serif;font-size:13px;line-height:1;text-align:left;color:#00315e;">
                    {!! $order->get_shipping_first_name() !!} {!! $order->get_shipping_last_name() !!}
                  </div>
                </td>
              </tr>
              <tr>
                <td align="left" style="font-size:0px;padding:10px 25px;padding-top:O;padding-bottom:0;word-break:break-word;">
                  <div style="font-family:Ubuntu, Helvetica, Arial, sans-serif;font-size:13px;line-height:1;text-align:left;color:#00315e;">
                    {!! $order->get_shipping_address_1() !!}
                  </div>
                </td>
              </tr>
              @if($order->get_shipping_address_2())
                <tr>
                  <td align="left" style="font-size:0px;padding:10px 25px;padding-top:O;padding-bottom:0;word-break:break-word;">
                    <div style="font-family:Ubuntu, Helvetica, Arial, sans-serif;font-size:13px;line-height:1;text-align:left;color:#00315e;">
                      {!! $order->get_shipping_address_2() !!}
                    </div>
                  </td>
                </tr>
              @endif
              <tr>
                <td align="left" style="font-size:0px;padding:10px 25px;padding-top:O;padding-bottom:0;word-break:break-word;">
                  <div style="font-family:Ubuntu, Helvetica, Arial, sans-serif;font-size:13px;line-height:1;text-align:left;color:#00315e;">
                    {!! $order->get_shipping_city() !!}, {!! $order->get_shipping_country() !!} {!! $order->get_shipping_postcode() !!}
                  </div>
                </td>
              </tr>
              <tr>
                <td align="left" style="font-size:0px;padding:10px 25px;padding-top:O;padding-bottom:0;word-break:break-word;">
                  <div style="font-family:Ubuntu, Helvetica, Arial, sans-serif;font-size:13px;line-height:1;text-align:left;color:#00315e;">
                    {!! $order->get_shipping_state() !!}
                  </div>
                </td>
              </tr>
            </table>
          </div>
          <!--[if mso | IE]>
          </td>

          <td
            class="" style="vertical-align:top;width:200px;"
          >
          <![endif]-->
          <div class="mj-column-px-200 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
            <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
              <tr>
                <td align="left" style="font-size:0px;padding:10px 25px;padding-top:O;padding-bottom:0;word-break:break-word;">
                  <div style="font-family:Ubuntu, Helvetica, Arial, sans-serif;font-size:13px;font-weight:bold;line-height:1;text-align:left;color:#00315e;">Billing address</div>
                </td>
              </tr>
              <tr>
                <td align="left" style="font-size:0px;padding:10px 25px;padding-top:O;padding-bottom:0;word-break:break-word;">
                  <div style="font-family:Ubuntu, Helvetica, Arial, sans-serif;font-size:13px;line-height:1;text-align:left;color:#00315e;">
                    {!! $order->get_billing_first_name() !!}
                  </div>
                </td>
              </tr>
              <tr>
                <td align="left" style="font-size:0px;padding:10px 25px;padding-top:O;padding-bottom:0;word-break:break-word;">
                  <div style="font-family:Ubuntu, Helvetica, Arial, sans-serif;font-size:13px;line-height:1;text-align:left;color:#00315e;">
                    {!! $order->get_billing_address_1() !!}
                  </div>
                </td>
              </tr>
              @if($order->get_billing_address_2())
                <tr>
                  <td align="left" style="font-size:0px;padding:10px 25px;padding-top:O;padding-bottom:0;word-break:break-word;">
                    <div style="font-family:Ubuntu, Helvetica, Arial, sans-serif;font-size:13px;line-height:1;text-align:left;color:#00315e;">
                      {!! $order->get_billing_address_2() !!}
                    </div>
                  </td>
                </tr>
              @endif
              <tr>
                <td align="left" style="font-size:0px;padding:10px 25px;padding-top:O;padding-bottom:0;word-break:break-word;">
                  <div style="font-family:Ubuntu, Helvetica, Arial, sans-serif;font-size:13px;line-height:1;text-align:left;color:#00315e;">
                    {!! $order->get_billing_city() !!}, {!! $order->get_billing_country() !!} {!! $order->get_billing_postcode() !!}
                  </div>
                </td>
              </tr>
              <tr>
                <td align="left" style="font-size:0px;padding:10px 25px;padding-top:O;padding-bottom:0;word-break:break-word;">
                  <div style="font-family:Ubuntu, Helvetica, Arial, sans-serif;font-size:13px;line-height:1;text-align:left;color:#00315e;">
                    {!! $order->get_billing_state() !!}
                  </div>
                </td>
              </tr>
            </table>
          </div>
          <!--[if mso | IE]>
          </td>

          </tr>

          </table>
          <![endif]-->
        </td>
      </tr>
      </tbody>
    </table>
  </div>
  <!--[if mso | IE]>
  </td>
  </tr>
  </table>
  <![endif]-->
  <!-- SECTION -->
</div>
