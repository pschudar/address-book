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
if (!$session->hasPermission($_SESSION['role_permissions'], 3)) {
    Utility::accessDenied();
}
$page_title = 'Address Book: Edit User';
require_once(SHARED_PATH . 'public_header.php');

$getId = filter_input(INPUT_GET, 'id', 519);

if (!isset($getId)) {
    Utility::redirectTo(Utility::urlFor('/staff/admins/index.php'));
}

# Select the stored user
$user = Admin::findByUserId($getId);

if (Utility::isPostRequest()) {
    
    # check if the user has permission to take this action
    if (!$session->hasPermission($_SESSION['role_permissions'], 3)) {
        # set an error if unauthorized
        $session->message('Unauthorized action', true);
        # forward to a page to show the error and explain why they're seeing it
        Utility::accessDenied();
    }

    # update record using post parameters
    $args = filter_input(INPUT_POST, 'admin', 513, FILTER_REQUIRE_ARRAY);
    # merge attributes
    $user->mergeAttributes($args);
    # save
    $result = $user->save();

    if ($result === true) {
        # if not an ajax request, set a success message
        $session->message('User ' . $user->fullName() . ' successfully updated');
        # redirect the browser back to the user list
        Utility::redirectTo(Utility::urlFor('/staff/admins/index.php'));
    }
}
?>
<main role="main"> 
    <div class='container mt-4'>
        <div class="my-3 p-3 bg-white rounded shadow">
            <?php echo $session->displayErrors($user->errors); ?>
            <div class="clearfix">&nbsp;</div>

            <form id='editUser' role='form' method='POST' action='<?php echo PHP_SELF . '?id=' . $getId; ?>' class="row g-3">
                <?php require_once('shared_form.php'); ?>
                <div class="col-12">
                    <button name='create_user' type="submit" class="btn btn-outline-success float-right">Edit User</button>
                </div>
            </form>

        </div>
    </div>
</main>

<?php
require_once(SHARED_PATH . 'public_footer.php');
