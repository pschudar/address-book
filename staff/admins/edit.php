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
if (!$session->hasPermission($_SESSION['role_permissions'], 7)) {
    Utility::accessDenied();
}
$page_title = 'Address Book: Edit User';
$showDeleteLink = false;
$addImageLabel = 'Add Image';
require_once(SHARED_PATH . 'public_header.php');

$getId = filter_input(INPUT_GET, 'id', 519);

if (!isset($getId)) {
    Utility::redirectTo(Utility::urlFor('/staff/admins/index.php'));
}

# Select the stored user
$entry = Admin::findByUserId($getId);

if (Utility::isPostRequest()) {

    # check if the user has permission to take this action
    if (!$session->hasPermission($_SESSION['role_permissions'], 7)) {
        # set an error if unauthorized
        $session->message('Unauthorized action', true);
        # forward to a page to show the error and explain why they're seeing it
        Utility::accessDenied();
    }

    # update record using post parameters
    $args = filter_input(INPUT_POST, 'admin', 513, FILTER_REQUIRE_ARRAY);
    # merge attributes
    $entry->mergeAttributes($args);
    # save
    $result = $entry->save();

    if ($result === true) {
        # if not an ajax request, set a success message
        $session->message('User ' . $entry->fullName() . ' successfully updated');
        # redirect the browser back to the user list
        Utility::redirectTo(Utility::urlFor('/staff/admins/index.php'));
    }
     else {
        $session->message('User ' . $entry->fullName() . ' update failed: ' . $entry::getLastError(), true);
        Utility::redirectTo(Utility::urlFor('/staff/admins/index.php'));
    }
}
if (Utility::hasPresence($entry->profile_image)) {
    $showDeleteLink = true;
    $addImageLabel = 'Update Image';
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
                                <h4 class="w3-hide-small w3-hide-medium"><?php echo Utility::h($entry->fullName()); ?></h4>
                                <hr class="w3-clear w3-hide-small w3-hide-medium">
                                <p class="w3-center" id="profileImage">
                                    <img id="profileImageTag" class="w3-circle scale elevation-5 profile-image" src="<?php echo $entry->displayProfileImage(); ?>" alt="Profile">
                                    <?php if(THIS_FILE === 'edit.php') { ?>
                                    <br>
                                    <input id="pwd_change" type="checkbox" class="w3-check" checked="checked"> <label for="pwd_change" class="toggle-btn mx-auto mb-0">Change Password</label>
                                    <?php } ?>
                                </p>
                                <div id="profileLinks" class="w3-center">
                                    <small>
                                        <a data-id="<?php echo Utility::hu($entry->id); ?>" data-uid="<?php echo Utility::hu($getId); ?>" data-toggle="modal" data-target="#addProfileImage" id="triggerAddProfileImage" class="triggerDelete" href="<?php echo Utility::urlFor('/staff/admins/image-upload.php?id=') . '' . Utility::hu($entry->id); ?>"><?php echo $addImageLabel; ?></a> <?php if ($showDeleteLink) { ?>| 
                                            <a data-id="<?php echo Utility::hu($entry->id); ?>" data-uid="<?php echo Utility::hu($getId); ?>" data-file="<?php echo Utility::h($entry->profile_image); ?>" data-toggle="modal" data-target="#deleteProfileImage" id="triggerDeleteProfileImage" class="triggerDelete" href="<?php echo Utility::urlFor('/staff/contacts/deleteimage.php?id=') . '' . Utility::hu($entry->id); ?>">Delete Image</a> <?php } ?>
                                    </small>
                                </div>
                                <div class="w3-clear">&nbsp;</div>
                                <form id='editUser' role='form' method='POST' action='<?php echo PHP_SELF . '?id=' . $getId; ?>' class="w3-row g-3">
                                    <?php require_once('shared_form.php'); ?>
                                    <div class="w3-col">
                                        <p>
                                            <a id="viewBtn" href="<?php echo Utility::urlFor('/staff/admins/view.php?id=') . $getId; ?>" class="w3-btn w3-border w3-border-theme w3-left">View Contact</a>
                                            <button id="saveBtn" name='edit_contact' type="submit" class="w3-btn w3-border w3-border-green w3-right">Save Changes</button>
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

            <!-- Right Column -->
            <?php require_once(SHARED_PATH . 'right-column.php'); ?>

            <!-- End Grid -->
        </div>

        <!-- End Page Container -->
    </div>

    <?php
    require_once(MODAL_PATH . 'delete_image_modal.php');
    require_once(MODAL_PATH . 'add_image_modal.php');
    require_once(SHARED_PATH . 'public_footer.php');
    