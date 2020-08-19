<?php

use \user\Admin,
    \utility\Utility;

require_once('../includes/initialize.php');

$errors = [];
$username = '';
$password = '';
$login_form_array = [];
$getDo = filter_input(INPUT_GET, 'do', 513);

if(isset($getDo) && $getDo == 'logout') {
    $errors[] = 'You are now logged out';
}

if (Utility::isPostRequest()) {

    # Initializing Login form details 
    $login_form_array = ['username', 'password'];
    foreach ($login_form_array as $key) {
        $$key = filter_input(INPUT_POST, trim($key), 513) ?? '';
    }

    // Validations
    if (Utility::isBlank($username) || (Utility::isBlank($password))) {
        $errors[] = 'All fields are required';
    }

    // if there were no errors, attempt to login
    // on the login page here, true must be the second argument
    if (Utility::isBlank($errors)) :
        #### $admin = Admin::findByUsername($username, true);
        $admin = Admin::findByUsername($username, true);
        # verify supplied credentials
        if ($admin && $admin->verify_password($password)) {
            # log the user in
            $session->login($admin);
            // @todo use ajax to apply a welcome message with users first name and a link for manual redirect
            Utility::redirectTo(Utility::urlFor('index.php'));
        } else {
            # invalid creds
            $errors[] = 'Invalid Credentials';
        }
    endif;
}
$page_title = 'Log in';
require_once(SHARED_PATH . 'login_header.php'); # this will have to be a separate header. There's no other way around it.
?>

<div class="w3-container">


    <div class="w3-modal-content w3-card-4 w3-theme-l5 w3-round ab-login-box">
  
      <div class="w3-center"><br>
        <img src="<?php echo Utility::urlFor('/assets/images/site/address.png'); ?>" alt="Logo" class="w3-circle scale w3-margin-top elevation-5">
      </div>

      <form role="form" method="post" id="ab-login-form" class="w3-container" action="login.php">
        <div class="w3-section">
          <label for="inputAccess" class="w3-label w3-text-dark">Access ID</label>
          <input name="username" type="text" id="inputAccess" class="w3-input w3-border w3-margin-bottom" value="<?php echo Utility::h($username); ?>" placeholder="Access ID"  autofocus="autofocus" required="required">
          <label for="inputPassword" class="w3-label w3-text-dark">Password</label>
          <input name="password" type="password" id="inputPassword" class="w3-input w3-border" placeholder="Password" required="required">
          <button id="submit" name="submit" class="w3-button w3-block w3-theme w3-section w3-padding" type="submit"><i class="fa fa-sign-in"></i> Login</button>
          <input class="w3-check w3-margin-top" type="checkbox" checked="checked"> <label class="w3-label w3-text-dark">Remember Me</label>
        </div>
      </form>

      <div class="w3-container w3-border-top w3-padding-16 w3-light-grey w3-round">
        <span class="w3-right w3-padding w3-hide-small">Forgot <a href="#">password?</a></span>
        <div id="statusMsg w3-left"><?php echo $session->displayErrors($errors); ?></div>
      </div>

    </div>

</div>

<?php
require_once(SHARED_PATH . 'public_footer.php');
