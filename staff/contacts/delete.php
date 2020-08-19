<?php

use \contacts\Listings,
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
if (!$session->hasPermission($_SESSION['role_permissions'], 4)) {
    Utility::accessDenied();
}

$getId = filter_input(INPUT_GET, 'id', 519);

if (!isset($getId)) {
    Utility::redirectTo(Utility::urlFor('/staff/contacts/index.php'));
}

# find the entry by id
$entry = Listings::findById($getId);

if ($entry == false) {
    #Utility::redirectTo(Utility::urlFor('/staff/contacts/index.php'));
    echo 'cannot find id ' . $getId;
}

if (Utility::isPostRequest()) {
    
    # check if the user has permission to take this action
    if (!$session->hasPermission($_SESSION['role_permissions'], 4)) {
        # set an error if unauthorized
        $session->message('Unauthorized action', true);
        # forward to a page to show the error and explain why they're seeing it
        Utility::accessDenied();
    }
    $result = $entry->delete();

    switch ($result) {
        case true:
            # delete profile image
            ImageXref::removeProfileImage($getId);
            # the article was deleted so advise the user
            $session->message('Contact ' . $entry->fullName() . ' successfully deleted');
            Utility::redirectTo(Utility::urlFor('/staff/contacts/index.php'));
            break;
        default:
            # the entry failed to delete so advise the user
            $session->message('There was an error while deleting ' . $entry->fullName() . '!', true);
            Utility::redirectTo(Utility::urlFor('/staff/contacts/index.php'));
            break;
    }
}

// removed the no-js fallback page 08.18.20
