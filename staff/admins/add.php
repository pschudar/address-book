<?php

use \user\Admin,
    \utility\Utility;

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

    $entry = new Admin($args);
    $result = $entry->save();

    if ($result === true) {
        $new_id = $entry->id;
        # if not an ajax request, set a success message
        $session->message('User ' . $entry->fullName() . ' successfully created');
        # redirect the browser back to the user list
        Utility::redirectTo(Utility::urlFor('/staff/admins/index.php'));
    }
} else {
    $entry = new Admin();
}
?>
<div id="page-container">
    <!-- Page Container -->
    <div id="content-wrap" class="w3-container w3-content">
        <!-- The Grid -->
        <div class="w3-row">

            <!-- Left Column -->
            <?php require_once(SHARED_PATH . 'left-column.php'); ?>
            <!-- Middle Column -->
            <main class="w3-col m7">

                <div class="w3-row-padding w3-margin-bottom">
                    <div class="w3-col m12">
                        <div class="w3-card w3-round w3-theme-l4">
                            <div class="w3-container w3-padding">
                                <i class="fa fa-fw fa-vcard-o fa-2x w3-right w3-text-theme w3-hide-small w3-hide-medium"></i>
                                <h4 class="">Add New User</h4>
                                <hr class="w3-clear w3-hide-small w3-hide-medium">

                                <div class="w3-clear">&nbsp;</div>
                                <form id='addUser' role='form' method='POST' action='<?php echo PHP_SELF; ?>' class="w3-row g-3">
                                    <?php require_once('shared_form.php'); ?>
                                    <div class="w3-col">
                                        <p>
                                            <a id="viewBtn" href="<?php echo Utility::urlFor('/staff/admins/index.php'); ?>" class="w3-btn w3-border w3-border-red w3-left w3-ripple">Cancel</a>
                                            <button id="saveBtn" name='create_user' type="submit" class="w3-btn w3-border w3-border-green w3-right">Add User</button>
                                        </p>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w3-row-padding w3-margin-bottom">
                    <div class="w3-col m12">
                        <div class="w3-card w3-round w3-theme-l4">
                            <div class="w3-container w3-padding">
                                <div class="w3-center"><strong>Status Messages</strong></div>
                                
                                <?php echo '<br>' . $session->displayErrors($entry->errors); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Middle Column -->
            </main>
            <div class="w3-clear w3-hide-large w3-hide-medium">&nbsp;</div>

            <!-- Right Column -->
            <?php require_once(SHARED_PATH . 'right-column.php'); ?>

            <!-- End Grid -->
        </div>

        <!-- End Page Container -->
    </div>

<?php
require_once(SHARED_PATH . 'public_footer.php');
