<?php

use \user\Admin,
    \utility\Utility,
    \paginate\Pagination;

require_once('../../includes/initialize.php');

$session->requireLogin();

if (!$session->hasPermission($_SESSION['role_permissions'], 1)) {
    Utility::redirectTo(Utility::urlFor('includes/shared/access_denied.php'));
}

$page_title = 'Address Book: View Admins';
require_once(SHARED_PATH . 'public_header.php');

# define get variable 'page'
$page = filter_input(INPUT_GET, 'page', 519);
# define current page
$current_page = $page ?? 1; # If page is not set, page is defaults to 1
# count records
$total_count = Admin::countAll();
# per page
$per_page = 10;
# instantiate paginate
$pagination = new Pagination($current_page, $per_page, $total_count);
$entries = Admin::getAll($per_page, $pagination->offset());

## Note: class='d-none d-md-table-cell' hides the cell below md break point
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
                        <th class="d-none d-md-table-cell" scope="col">Status</th>
                        <th scope="col">Joined</th>
                        <th scope="col">Role</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($entries as $entry) : ?>
                        <tr>
                            <th scope="row"><?php echo Admin::count(); ?></th>
                            <td><img class="img-fluid img-profile rounded-circle" src="<?php echo $entry->displayProfileImage(); ?>" alt="Profile" height="40" width="40"></td>
                            <td><?php echo $entry->fullName(true); # pass true for Last, First format      ?>
                                <div class="clearfix"></div>

                                <span id="<?php echo Utility::h($entry->id); ?>" class="hidden small">
                                    <?php echo $entry->displayAdminLinks('admins', $session); ?>
                                </span>
                                
                            </td> 
                            <td class="d-none d-md-table-cell"><?php echo $entry->printEmail(); ?></td>
                            <td class="d-none d-md-table-cell"><?php echo Utility::h($entry->status); ?></td>
                            <td><?php echo Utility::formatDate($entry->date_created, 'm/d/Y'); ?></td>
                            <td><?php echo Utility::h($entry->role_name); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="mx-auto">
                <?php echo $pagination->pageLinks(Utility::urlFor('/staff/admins/index.php')); ?>
            </div>
        </div>
    </div>
</main>

<?php
require_once(MODAL_PATH . 'delete_modal.php');
require_once(SHARED_PATH . 'public_footer.php');
