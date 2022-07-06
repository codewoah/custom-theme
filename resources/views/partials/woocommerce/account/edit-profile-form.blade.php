 <div class="form-edit-address__row-wide">
    <input placeholder="First name" class="required" type="text" name="user_first_name" id="user_first_name" value="{!! $user->first_name !!}">
    <input placeholder="Last name" class="required" type="text" name="user_last_name" id="user_last_name" value="{!!$user->last_name !!}">
  </div>
  <div class="form-edit-address__row-full">
    <input placeholder="Email" type="text" class="required" name="user_email"  id="user_email" value="{!! $user->user_email !!}">
  </div>
