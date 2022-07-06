<div class="my-accout-login">

  <form class="woocommerce-form woocommerce-form-login login" method="post">
    <?php do_action( 'woocommerce_login_form_start' ); ?>

    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
      <input placeholder="Email" type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
    </p>
    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
      <input placeholder="Password" class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="current-password" />
    </p>

     <?php do_action( 'woocommerce_login_form' ); ?>

      <?php do_action( 'woocommerce_before_customer_login_form' ); ?>


      <p class="form-row my-accout-login__flex">
      <span class="flex flex--a-center">
      <input checked class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" />
      <label for="rememberme" class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
        <?php esc_html_e( 'Remember me', 'woocommerce' ); ?>
      </label>
      </span>
      <a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Forgot password', 'woocommerce' ); ?></a>
    </p>

      <?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
    <button type="submit" class="btn btn--blue my-accout-login__login" name="login" value="<?php esc_attr_e( 'Log in', 'woocommerce' ); ?>"><?php esc_html_e( 'Log in', 'woocommerce' ); ?></button>


    <?php do_action( 'woocommerce_login_form_end' ); ?>

  </form>
</div>
