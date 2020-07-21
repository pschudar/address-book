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
<main role="main"> 
    <div class='container mt-4'>
        <div class="my-3 p-3 bg-white rounded shadow">
            <div class="clearfix">&nbsp;</div>

            <form id='addContact' role='form' method='POST' action='<?php echo PHP_SELF; ?>' class="row g-3">
                <?php require_once('shared_form.php'); ?>
                <div class="col-12">
                    <button name='create_contact' type="submit" class="btn btn-outline-success float-right">Create Contact</button>
                </div>
            </form>

        </div>
    </div>
</main>

<?php
require_once(SHARED_PATH . 'public_footer.php');
