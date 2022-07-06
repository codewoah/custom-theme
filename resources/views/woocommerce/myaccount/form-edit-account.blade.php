@php
  /** @var WP_User $user */
  $user = get_user_by('id',get_current_user_id());
@endphp

<div class="inner">
<form action="#" class="form-edit-address" id="edit-user-profile">
  <h3 class="title">Account details</h3>
  @include('partials.woocommerce.account.edit-profile-form')
  <h3 class="title">Password change</h3>
  @include('partials.woocommerce.account.edit-password-form')
  <button type="submit" class="btn btn--blue">Save</button>
</form>
</div>
