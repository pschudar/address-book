<?php

use \utility\Utility;

$userRoles = \user\Admin::getRoles();

# user status
$selected0 = '';
$selected1 = 'selected';
switch ($entry->status) {
    case 0:
        $selected0 = 'selected';
        break;
    case 1:
        $selected1 = 'selected';
        break;
}
?>
<div class="w3-row-padding w3-margin-bottom-custom">
    <div class="w3-third">
        <label for="firstName" class="w3-label">First Name</label>
        <input required placeholder='First Name' type="text" class="w3-input w3-border w3-theme-l5" id="firstName" name='admin[name_first]' value="<?php echo Utility::h($entry->name_first); ?>">
    </div>
    <div class="w3-third">
        <label for="lastName" class="w3-label">Last Name</label>
        <input required placeholder='Last Name' type="text" class="w3-input w3-border w3-theme-l5" id="lastName" name='admin[name_last]' value="<?php echo Utility::h($entry->name_last); ?>">
    </div>
    <div class="w3-third">
        <label for="username" class="w3-label">Username</label>
        <input required placeholder='Username' type="text" class="w3-input w3-border w3-theme-l5" id="username" name='admin[username]' value="<?php echo Utility::h($entry->username); ?>">
    </div>
</div>
<div class="w3-row-padding w3-margin-bottom-custom">
    <div class="w3-col">
        <label for="emailAddress" class="w3-label">Email Address</label>
        <input required placeholder='user@domain.com' type="email" class="w3-input w3-border w3-theme-l5" id="emailAddress" name='admin[email]' value="<?php echo Utility::h($entry->email); ?>">
    </div>
</div>
<div id="password" class="w3-row-padding w3-margin-bottom-custom">
    <div id="userPassword" class="w3-half">
        <label for="password" class="w3-label">Password</label>
        <input type="password" name="admin[password]" value="" class="w3-input w3-border w3-theme-l5" id="password" placeholder="Password">
    </div>
    <div id="userPasswordConfirm" class="w3-half">
        <label for="conf_password" class="w3-label">Confirm Password</label>
        <input type="password" name="admin[confirm_password]" value="" class="w3-input w3-border w3-theme-l5" id="conf_password" placeholder="Confirm Password">
    </div>
</div>
<div class="w3-row-padding">
    <div class="w3-half">
        <label for="userRoles" class="w3-label">Role</label>
        <select required name='admin[role_id]' class='w3-select w3-border w3-theme-l5' id='userRoles'>
            <option value=''>Select a Role</option>
            <?php
            foreach ($userRoles as $role) {
                $selected = null;
                if ($entry->role_id == $role->role_id) {
                    $selected = 'selected';
                }
                echo "<option value='$role->role_id' $selected>$role->role_id  -  $role->role_name</option>";
            }
            ?>
        </select>
    </div>
    <div class="w3-half">
        <label for="status" class="w3-label">Status</label>
        <select required name="admin[status]" class="w3-select w3-border w3-theme-l5" id="status">
            <option value=''>Select User Status</option>
            <option value='0' <?php echo $selected0; ?>>0 - Inactive</option>
            <option value='1' <?php echo $selected1; ?>>1 - Active</option>
        </select>
    </div>
</div>