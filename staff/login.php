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

<div class="container">
    <div class="col-md-4 mx-auto">
        <img class="rounded-circle mx-auto d-block mt-5 elevation-5" src="<?php echo Utility::urlFor('/assets/images/site/address.png'); ?>" alt="logo">
    <div class="card card-login mt-3">
        <div class="card-body">
            <form role="form" id="pb-login-form" action="login.php" method="post">
                <div class="form-group">
                    <div class="form-label-group">
                        <label for="inputAccess">Access ID</label>
                        <input name="username" type="text" id="inputAccess" class="form-control" value="<?php echo Utility::h($username); ?>" placeholder="Access ID"  autofocus="autofocus" required="required">
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-label-group">
                        <label for="inputPassword">Password</label>
                        <input name="password" type="password" id="inputPassword" class="form-control" placeholder="Password" required="required">
                    </div>
                </div>
                <div class="clearfix">&nbsp;</div>
                <div class="form-group">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" value="remember-me">
                            Remember Password
                        </label>
                    </div>
                </div>
                <div class="clearfix">&nbsp;</div>
                <button id="submit" name="submit" type="submit" class="btn btn-outline-dark btn-block btn-flat"><span class="os os-login"></span> Login</button>
            </form>
            <div class="text-center">
                <a class="d-block small mt-3" href="#">Register an Account</a>
                <a class="d-block small" href="#">Forgot Password?</a>
            </div>
        </div>
        <div id="statusMsg"><?php echo $session->displayErrors($errors); ?></div>
    </div>
    </div>
</div>
<?php
require_once(SHARED_PATH . 'public_footer.php');
