<?php

use \contacts\Listings,
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
if (!$session->hasPermission($_SESSION['role_permissions'], 3)) {
    Utility::accessDenied();
}

require_once('usa_states.php');
$page_title = 'Address Book: Edit Contact';
$showDeleteLink = false;
$addImageLabel = 'Add Image';
require_once(SHARED_PATH . 'public_header.php');

$getId = filter_input(INPUT_GET, 'id', 519);

if (!isset($getId)) {
    Utility::redirectTo(Utility::urlFor('/staff/contacts/index.php'));
}

# Select the stored entry
$entry = Listings::findByContactId($getId);

if (Utility::isPostRequest()) {

    # check if the user has permission to take this action
    if (!$session->hasPermission($_SESSION['role_permissions'], 3)) {
        # set an error if unauthorized
        $session->message('Unauthorized action', true);
        # forward to a page to show the error and explain why they're seeing it
        Utility::accessDenied();
    }

    # Create a address book record using post array as Listing params
    # filter contact array as string
    $args = filter_input(INPUT_POST, 'contact', 513, FILTER_REQUIRE_ARRAY);

    #filter number array as int
    $numbers = filter_input(INPUT_POST, 'number', 519, FILTER_REQUIRE_ARRAY);

    # assign numbers to the args array
    foreach ($numbers as $key => $val) {
        switch (Utility::hasPresence($key)) {
            case true:
                # if $val is not empty
                $args[$key] = $val;
                # assigns $args['phone_home'] = 'numbers posted';
                break;
        }
    }

    $entry->mergeAttributes($args);
    $result = $entry->save();

    if ($result === true) {
        # if not an ajax request, set a success message
        $session->message('Contact ' . $entry->fullName() . ' successfully updated');
        # redirect the browser back to the contact list
        Utility::redirectTo(Utility::urlFor('/staff/contacts/index.php'));
    } else {
        $session->message('Contact ' . $entry->fullName() . ' update failed: ' . $entry::getLastError(), true);
        Utility::redirectTo(Utility::urlFor('/staff/contacts/index.php'));
    }
}
if (Utility::hasPresence($entry->profile_image)) {
    $showDeleteLink = true;
    $addImageLabel = 'Update Image';
}
?>
<main role="main"> 
    <div class='container mt-4'>
        <div class="my-3 p-3 bg-white rounded shadow">

            <div class="card-body">

                <span id="profileImage">
                    <img class="img-fluid img-thumbnail rounded-circle d-block scale avatar mx-auto elevation-5" src="<?php echo $entry->displayProfileImage(); ?>" alt="Profile">
                </span>
                <div id="profileLinks" class="text-center">
                    <small>
                        <a data-id="<?php echo Utility::hu($entry->id); ?>" data-uid="<?php echo Utility::hu($getId); ?>" data-toggle="modal" data-target="#addProfileImage" id="triggerAddProfileImage" class="triggerDelete text-primary" href="<?php echo Utility::urlFor('/staff/contacts/image-upload.php?id=') . '' . Utility::hu($entry->id); ?>"><?php echo $addImageLabel; ?></a> <?php if ($showDeleteLink) { ?>| 
                            <a data-id="<?php echo Utility::hu($entry->id); ?>" data-uid="<?php echo Utility::hu($getId); ?>" data-file="<?php echo Utility::h($entry->profile_image); ?>" data-toggle="modal" data-target="#deleteProfileImage" id="triggerDeleteProfileImage" class="triggerDelete text-danger" href="<?php echo Utility::urlFor('/staff/contacts/deleteimage.php?id=') . '' . Utility::hu($entry->id); ?>">Delete Image</a> <?php } ?>
                    </small>
                </div>
                <form id='editContact' role='form' method='POST' action='<?php echo PHP_SELF . '?id=' . $getId; ?>' class="row g-3">
                    <?php require_once('shared_form.php'); ?>
                    <div class="col-12">
                        <a href="<?php echo Utility::urlFor('/staff/contacts/view.php?id=') . $getId; ?>" class="btn btn-outline-primary float-left">View Contact</a>
                        <button name='edit_contact' type="submit" class="btn btn-outline-success float-right">Edit Contact</button>
                    </div>
                </form>

            </div>


        </div>
    </div>
</main>

<?php
require_once(MODAL_PATH . 'delete_image_modal.php');
require_once(MODAL_PATH . 'add_image_modal.php');
require_once(SHARED_PATH . 'public_footer.php');
