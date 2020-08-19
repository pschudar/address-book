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
if (!$session->hasPermission($_SESSION['role_permissions'], 2)) {
    Utility::accessDenied();
}

require_once('usa_states.php');
$page_title = 'Address Book: Add Contact';
require_once(SHARED_PATH . 'public_header.php');


## Note: class='d-none d-md-table-cell' hides the cell below md break point
if (Utility::isPostRequest()) {

    # check if the user has permission to take this action
    if (!$session->hasPermission($_SESSION['role_permissions'], 2)) {
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

    $entry = new Listings($args);
    $result = $entry->save();

    if ($result === true) {
        # if not an ajax request, set a success message
        $session->message('Contact ' . $entry->fullName() . ' successfully created');
        # redirect teh browser back to the blog list
        Utility::redirectTo(Utility::urlFor('/staff/contacts/index.php'));
    }
} else {
    $entry = new Listings();
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

                <div class="w3-row-padding">
                    <div class="w3-col m12">
                        <div class="w3-card w3-round w3-theme-l4">
                            <div class="w3-container w3-padding">
                                <i class="fa fa-fw fa-vcard-o fa-2x w3-right w3-text-theme w3-hide-small w3-hide-medium"></i>
                                <h4 class="">Add New Contact</h4>
                                <hr class="w3-clear w3-hide-small w3-hide-medium">

                                <div class="w3-clear">&nbsp;</div>
                                <form id='addContact' role='form' method='POST' action='<?php echo PHP_SELF; ?>' class="w3-row g-3">
                                    <?php require_once('shared_form.php'); ?>
                                    <div class="w3-col">
                                        <p>
                                            <a id="viewBtn" href="<?php echo Utility::urlFor('/staff/contacts/index.php'); ?>" class="w3-btn w3-border w3-border-red w3-left w3-ripple">Cancel</a>
                                            <button id="saveBtn" name='edit_contact' type="submit" class="w3-btn w3-border w3-border-green w3-right">Add Contact</button>
                                        </p>
                                    </div>
                                </form>
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
    