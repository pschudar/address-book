<?php

use \utility\Utility;

$userRoles = \user\Admin::getRoles();

# user status
$selected0 = '';
$selected1 = '';
switch ($user->status) {
    case 0:
        $selected0 = 'selected';
        break;
    case 1:
        $selected1 = 'selected';
        break;
}
?>
<div class="col-md-4">
    <label for="firstName" class="form-label">First Name</label>
    <input required placeholder='First Name' type="text" class="form-control" id="firstName" name='admin[name_first]' value="<?php echo Utility::h($user->name_first); ?>">
</div>
<div class="col-md-4">
    <label for="lastName" class="form-label">Last Name</label>
    <input required placeholder='Last Name' type="text" class="form-control" id="lastName" name='admin[name_last]' value="<?php echo Utility::h($user->name_last); ?>">
</div>
<div class="col-md-4">
    <label for="username" class="form-label">Username</label>
    <input required placeholder='Username' type="text" class="form-control" id="username" name='admin[username]' value="<?php echo Utility::h($user->username); ?>">
</div>
<div class="col-12">
    <label for="emailAddress" class="form-label">Email Address</label>
    <input required placeholder='user@domain.com' type="email" class="form-control" id="emailAddress" name='admin[email]' value="<?php echo Utility::h($user->email); ?>">
</div>
<div id="userPassword" class="col-md-6">
    <label for="password">Password</label>
    <input type="password" name="admin[password]" value="" class="form-control" id="password" placeholder="Password">
</div>
<div id="userPasswordConfirm" class="col-md-6">
    <label for="conf_password">Confirm Password</label>
    <input type="password" name="admin[confirm_password]" value="" class="form-control" id="conf_password" placeholder="Confirm Password">
</div>
<div class="col-md-6">
    <label for="userRoles" class="form-label">Role</label>
    <select required name='admin[role_id]' class='form-control' id='userRoles'>
        <option value=''>Select a Role</option>
        <?php
        foreach ($userRoles as $role) {
            $selected = null;
            if ($user->role_id == $role->role_id) {
                $selected = 'selected';
            }
            echo "<option value='$role->role_id' $selected>$role->role_id  -  $role->role_name</option>";
        }
        ?>
    </select>
</div>
<div class="col-md-6">
    <label for="status" class="form-label">Status</label>
    <select required name="admin[status]" class="form-control" id="status">
        <option value=''>Select User Status</option>
        <option value='0' <?php echo $selected0; ?>>0 - Inactive</option>
        <option value='1' <?php echo $selected1; ?>>1 - Active</option>
    </select>
</div>