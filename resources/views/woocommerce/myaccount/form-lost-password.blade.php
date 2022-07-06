<div class="my-accout-login">

  <form method="post" class="woocommerce-ResetPassword lost_reset_password">

    <p><?php echo apply_filters( 'woocommerce_lost_password_message', esc_html__( 'Enter your email and we\'ll send you reset instructions.', 'woocommerce' ) ); ?></p><?php // @codingStandardsIgnoreLine ?>

    <p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
      <input placeholder="Email" class="woocommerce-Input woocommerce-Input--text input-text" type="text" name="user_login" id="user_login" autocomplete="username" />
    </p>

    <div class="clear"></div>

    <?php do_action( 'woocommerce_before_customer_login_form' ); ?>

    <?php do_action( 'woocommerce_lostpassword_form' ); ?>

    <p class="woocommerce-form-row form-row">
      <input type="hidden" name="wc_reset_password" value="true" />
      <button type="submit" class="btn btn--blue my-accout-login__login reset-pass-btn" value="<?php esc_attr_e( 'Reset password', 'woocommerce' ); ?>"><?php esc_html_e( 'Reset password', 'woocommerce' ); ?></button>
    </p>

    <?php wp_nonce_field( 'lost_password', 'woocommerce-lost-password-nonce' ); ?>

  </form>
</div>
