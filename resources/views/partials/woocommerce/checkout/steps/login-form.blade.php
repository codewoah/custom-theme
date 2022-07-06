<div class="views-wrapper">
<!-- signin form -->
<div class="login-form view" id="signin-form">
  <h3>
    Log in to your Willo account
    <span>Are you a new customer? <a href="#" data-updater-view="checkout-hello-form">Create your account</a></span>
  </h3>
  <div class="login-form__wrapper form">
    <input class="form__email grey-border" type="email" placeholder="Email">
    <input class="form__password  grey-border" type="password" placeholder="Password">
  </div>
  <div class="login-form__footer">
    <span class="login-form__footer__remember">
      <span class="willo-checkbox willo-checkbox--checked">
        <input id="remember_me" type="checkbox" checked>
        <label class="" for="remember_me">Remember me</label>
      </span>
    </span>
    <a href="<?php echo esc_url( wp_lostpassword_url() ); ?>" target="_blank" rel="noreferrer noopener">Forgot password</a>
  </div>

  <div class="login-form__submit">
    <button style="display: none" type="button" class="btn btn--blue" id="signin-form__submit">Continue</button>
  </div>


</div>
<!-- checkout form -->
<div class="login-form active view" id="checkout-hello-form">
  <h3>
    Hi, let's create your account
    <span> Already have a Willo account? <a href="#" data-updater-view="signin-form">Log in now</a></span>
  </h3>
  <div class="login-form__wrapper">
    <input  class="form__email  grey-border" type="email" placeholder="Email" id="checkout-hello-form__email">
    <input  class="form__password  grey-border" type="password" placeholder="Password" id="checkout-hello-form__password">
  </div>
  <div class="login-form__footer">
    <span class="login-form__footer__remember">
      <span class="willo-checkbox willo-checkbox--checked">
        <input id="checkout-hello-form__suscribe" class="grey-border" type="checkbox" checked>
        <label class="" for="checkout-hello-form__suscribe">
          @php _e('Subscribe to Willo news and updates','willo'); @endphp
        </label>
        <small>
          @php echo sprintf(__(' By creating an account, you agree to our <a href="%s">Privacy Policy</a> and <a href="%s" target="_blank" rel="noopener noreferrer">Terms</a>.<br />Unsubscribe anytime.'),'', get_page_link(get_option('woocommerce_terms_page_id'))) @endphp

        </small>
      </span>
    </span>
  </div>
  <div class="login-form__submit">
    <button style="display: none" type="button" class="btn btn--blue" id="checkout-hello-form__submit">Continue</button>
  </div>
</div>
<!-- forgot pass -->
<div class="login-form view" id="forgot-pass-form">
    <h3>
      Forgot your password?
      <span>Remember your password? <a href="#" data-updater-view="signin-form">Sign in</a></span>
    </h3>
    <div class="login-form__wrapper">
      <input type="email" placeholder="Email">
    </div>
    <div class="login-form__submit">
      <button type="button" class="btn btn--blue">Reset my password</button>
    </div>
  </div>
</div>
