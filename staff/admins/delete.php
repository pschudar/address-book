<?php

use \user\Admin,
    \utility\Utility,
    \file\ImageXref;

require_once('../../includes/initialize.php');
$session->requireLogin();

/**
 * Site Permissions - Quick Notes
 * 
 * 1: View Contacts     5: View Admins
 * 2: Create Contacts   6: Create Admins
 * 3: Update Contacts   7: Update Admins
 * 4: Delete Contacts   8: Delete Admins
 */
if(!$session->hasPermission($_SESSION['role_permissions'], 4)) {
    Utility::accessDenied();
}

$getId = filter_input(INPUT_GET, 'id', 519);

if (!isset($getId)) {
    Utility::redirectTo(Utility::urlFor('/staff/admins/index.php'));
}

# find the entry by id
$entry = Admin::findById($getId);

if ($entry == false) {
    Utility::redirectTo(Utility::urlFor('/staff/admins/index.php'));
}

if (Utility::isPostRequest()) {
    $result = $entry->delete();

    switch ($result) {
        case true:
            # delete profile image
            ## ImageXref::removeProfileImage($getId); ## Not yet - I want to rewrite the image uploading before I implement it here
            # the user was deleted so advise the user
            $session->message('User ' . $entry->fullName() . ' successfully deleted');
            Utility::redirectTo(Utility::urlFor('/staff/admins/index.php'));
            break;
        default:
            # the entry failed to delete so advise the user
            $session->message('There was an error while deleting ' . $entry->fullName() . '!', true);
            Utility::redirectTo(Utility::urlFor('/staff/admins/index.php'));
            break;
    }
}

$page_title = 'Delete User';
require_once(SHARED_PATH . 'public_header.php');
?>
<main role="main"> 
    <div class='container mt-4'>
        <div class="my-3 p-3 bg-white rounded shadow">
            <div class="card">
                <div class="card-header">
                    Delete Admin: <?php echo $entry->fullName(); ?>
                </div>
                <div class="card-body">
                    <h5 class="card-title">Are you sure?</h5>
                    <p class="card-text">This action is permanent and cannot be undone.</p>
                    <form id="deleteContactForm2" action="<?php echo \utility\Utility::urlFor('/staff/admins/delete.php?id=') . $entry->id; ?>" method="post">
                        <a href="<?php echo \utility\Utility::urlFor('/staff/admins/index.php'); ?>" class="btn btn-outline-secondary">Cancel</a>
                        <input type="submit" class="btn btn-outline-danger" name="commit" value="Delete User" />
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
<?php
require_once(SHARED_PATH . 'public_footer.php');
