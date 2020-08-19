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

<div id="page-container">
    <!-- Page Container -->
    <div id="content-wrap" class="w3-container w3-content">
        <!-- The Grid -->
        <div class="w3-row">

            <!-- Left Column -->
            <?php require_once(SHARED_PATH . 'left-column.php'); ?>
            <!-- Middle Column -->
            <main class="w3-col m7">

                <div class="w3-row-padding">
                    <span id="sessionMsg"><?php echo $session->displaySessionMessage(); ?></span>
                    <div class="w3-col m12">
                        <div class="w3-card w3-round w3-theme-l4">
                            <div class="w3-container w3-padding">
                                <i class="fa fa-fw fa-vcard-o fa-2x w3-right w3-text-theme w3-hide-small w3-hide-medium"></i>
                                <h4 class="w3-hide-small w3-hide-medium"><?php echo Utility::h($entry->fullName()); ?></h4>
                                <hr class="w3-clear w3-hide-small w3-hide-medium">
                                <p class="w3-center" id="profileImage">
                                    <img class="w3-circle scale elevation-5 profile-image" src="<?php echo $entry->displayProfileImage(); ?>" alt="Profile">
                                </p>
                                <h4 class="w3-hide-large w3-center"><?php echo Utility::h($entry->fullName()); ?></h4>

                                <ul class="w3-ul w3-large w3-left">
                                    <li><article> <?php echo $entry->printAddress() . '<br>' . $entry->printPhoneNumbers(); ?></article></li>
                                </ul>
                                <article class="w3-right">
                                    <p><i class="fa fa-birthday-cake fa-fw w3-margin-right w3-text-theme"></i><?php echo Utility::formatDate($entry->date_of_birth, 'm/d/Y', 'Y-m-d'); ?></p>
                                    <p><i class="fa fa-birthday-cake fa-fw w3-margin-right w3-text-theme"></i><?php echo $entry->formatAge($entry->date_of_birth, false); # pass in true on index.php only    ?></p>
                                    <p><i class="fa fa-envelope fa-fw w3-margin-right w3-text-theme"></i><?php echo $entry->printEmail(false); # pass in false unless on index.php    ?></p>
                                </article>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="w3-container w3-card-2 w3-theme-l5 w3-round w3-margin w3-center"><br>
                    <p>
                        <span class="w3-text-theme">Contact Created: <?php echo Utility::formatDate($entry->date_created, 'm/d/Y'); ?></span>
                    </p>
                    <p>
                        <a id="contactHome" href="<?php echo Utility::urlFor('/staff/contacts/index.php'); ?>" class="w3-btn w3-theme-d3"><i class="fa fa-address-book-o"></i> &nbsp; Home</a>
                        <a id="editContact" href="<?php echo Utility::urlFor('/staff/contacts/edit.php?id=') . $getId; ?>" class="w3-btn w3-theme-d1"><i class="fa fa-pencil"></i> &nbsp; Edit</a>
                        <a id="askRemoveContact" data-id="<?php echo $entry->id; ?>" data-name="<?php echo Utility::h($entry->fullName()); ?>" data-toggle="modal" data-target="#deleteContact" href="<?php echo Utility::urlFor('/staff/contacts/delete.php?id=') . $getId;  ?>" class="w3-btn w3-theme-d2 triggerDelete"><i class="fa fa-times"></i> &nbsp; Delete</a>
                    </p>
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
    require_once(MODAL_PATH . 'delete_modal.php');
    require_once(SHARED_PATH . 'public_footer.php');
    