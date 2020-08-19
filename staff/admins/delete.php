<?php

use \user\Admin,
    \utility\Utility,
    \file\UserImageXref;

require_once('../../includes/initialize.php');
require_once('../../includes/classes/file/UserImageXref.php');
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
            UserImageXref::removeProfileImage($getId);
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

// removed the no-js fallback page 08.18.20
