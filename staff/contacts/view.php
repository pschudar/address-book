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
if (!$session->hasPermission($_SESSION['role_permissions'], 1)) {
    Utility::accessDenied();
}

$page_title = 'Address Book: View Contact';

require_once(SHARED_PATH . 'public_header.php');

$getId = filter_input(INPUT_GET, 'id', 519);

if (!isset($getId)) {
    Utility::redirectTo(Utility::urlFor('index.php'));
}

# Select the stored entry
$entry = Listings::findByContactId($getId);
?>
<main role="main"> 
    <div class='container mt-4'>
        <div class="my-3 p-3 bg-white rounded shadow">

            <div class="card-body text-center">
                <span id="profileImage">
                    <img class="img-fluid img-thumbnail rounded-circle d-block scale avatar mx-auto elevation-5" src="<?php echo $entry->displayProfileImage(); ?>" alt="Profile">
                </span>
                <h4 class="card-title"><?php echo Utility::h($entry->fullName()); ?></h4>
                <h6 class="card-subtitle mb-2 text-muted small"><?php echo $entry->printEmail(); ?></h6>
                <div class="card-text">
                    <address>
                        <?php
                        echo $entry->printAddress();
                        echo $entry->printPhoneNumbers();
                        ?>
                    </address>
                </div>
                <a href="<?php echo Utility::urlFor('/staff/contacts/edit.php?id=') . $getId; ?>" class="btn btn-outline-secondary">Edit Contact</a>
                <div class="entry-created">
                    <p class="entry-date small text-muted">Contact Created: <?php echo Utility::formatDate($entry->date_created, 'm/d/Y'); ?></p>
                </div>
            </div>

        </div>
    </div>
</main>

<?php
require_once(SHARED_PATH . 'public_footer.php');
