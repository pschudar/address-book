<?php

use \contacts\Listings,
    \utility\Utility,
    \paginate\Pagination;

require_once('../../includes/initialize.php');

$session->requireLogin();

if (!$session->hasPermission($_SESSION['role_permissions'], 1)) {
    Utility::redirectTo(Utility::urlFor('includes/shared/access_denied.php'));
}

$page_title = 'Address Book: View Contacts';
require_once(SHARED_PATH . 'public_header.php');

# define get variable 'page'
$page = filter_input(INPUT_GET, 'page', 519);
# define current page
$current_page = $page ?? 1; # If page is not set, page is defaults to 1
# count records
$total_count = Listings::countAll();
# per page
$per_page = 10;
# instantiate paginate
$pagination = new Pagination($current_page, $per_page, $total_count);
$entries = Listings::getAll($per_page, $pagination->offset());


## Note: class='d-none d-md-table-cell' hides the cell below md break point

switch ($total_count) :
    case 0:
        require_once(PROJECT_PATH . DS . 'staff' . DS . 'contacts' . DS . 'empty.php');
        break;
    default:

?>
<main role="main"> 
    <div class='container mt-4'>
        <div class="my-3 p-3 bg-white rounded shadow">
            <span id="sessionMsg"><?php echo $session->displaySessionMessage(); ?></span>

            <table class="table table-responsive media-body pb-3 lh-125 border-bottom border-gray">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Image</th>
                        <th scope="col">Name</th>
                        <th class="d-none d-md-table-cell" scope="col">Email</th>
                        <th class="d-none d-md-table-cell" scope="col">Home Phone</th>
                        <th scope="col">Mobile Phone</th>
                        <th scope="col">Age</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($entries as $entry) : ?>
                        <tr>
                            <th scope="row"><?php echo Listings::count(); ?></th>
                            <td><img class="img-fluid img-profile rounded-circle" src="<?php echo $entry->displayProfileImage(); ?>" alt="Profile" height="40" width="40"></td>
                            <td><?php echo $entry->fullName(true); # pass true for Last, First format       ?>
                                <div class="clearfix"></div>

                                <span id="<?php echo Utility::h($entry->id); ?>" class="hidden small">
                                    <?php echo $entry->displayAdminLinks('contacts', $session); ?>
                                </span>

                            </td> 
                            <td class="d-none d-md-table-cell"><?php echo $entry->printEmail(); ?></td>
                            <td class="d-none d-md-table-cell"><?php echo Listings::filterNumber($entry->phone_home); ?></td>
                            <td><?php echo Listings::filterNumber($entry->phone_mobile); ?></td>
                            <td><?php echo $entry->formatAge($entry->date_of_birth); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="mx-auto">
                <?php echo $pagination->pageLinks(Utility::urlFor('/staff/contacts/index.php')); ?>
            </div>
        </div>
    </div>
</main>

<?php
break;
endswitch;
require_once(MODAL_PATH . 'delete_modal.php');
require_once(SHARED_PATH . 'public_footer.php');
