<?php

use \user\Admin,
    \utility\Utility;

require_once('../../includes/initialize.php');
$session->requireLogin();
/**
 * Contact Permissions - Quick Notes
 * 
 * 1: View Contacts
 * 2: Create Contacts
 * 3: Update Contacts
 * 4: Delete Contacts
 */
if (!$session->hasPermission($_SESSION['role_permissions'], 2)) {
    Utility::accessDenied();
}
$page_title = 'Address Book: Add New User';
require_once(SHARED_PATH . 'public_header.php');

if (Utility::isPostRequest()) {
    # ensure user has authorization
    if (!$session->hasPermission($_SESSION['role_permissions'], 2)) {
        Utility::accessDenied();
    }

    # Create record using post parameters
    $args = filter_input(INPUT_POST, 'admin', 513, FILTER_REQUIRE_ARRAY);

    $user = new Admin($args);
    $result = $user->save();

    if ($result === true) {
        $new_id = $user->id;
        # if not an ajax request, set a success message
        $session->message('User ' . $user->fullName() . ' successfully created');
        # redirect the browser back to the user list
        Utility::redirectTo(Utility::urlFor('/staff/admins/index.php'));
    }
} else {
    $user = new Admin();
}
?>
<main role="main"> 
    <div class='container mt-4'>
        <div class="my-3 p-3 bg-white rounded shadow">
            <?php echo $session->displayErrors($user->errors); ?>
            <div class="clearfix">&nbsp;</div>

            <form id='addUser' role='form' method='POST' action='<?php echo PHP_SELF; ?>' class="row g-3">
                <?php require_once('shared_form.php'); ?>
                <div class="col-12">
                    <button name='create_user' type="submit" class="btn btn-outline-success float-right">Create User</button>
                </div>
            </form>

        </div>
    </div>
</main>

<?php
require_once(SHARED_PATH . 'public_footer.php');
