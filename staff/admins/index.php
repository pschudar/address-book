<?php

use \user\Admin,
    \utility\Utility,
    \paginate\Pagination;

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
if (!$session->hasPermission($_SESSION['role_permissions'], 5)) {
            Utility::accessDenied();
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

switch ($total_count) :
    case 0:
        require_once(PROJECT_PATH . DS . 'staff' . DS . 'admins' . DS . 'empty.php');
        break;
    default:
        ?>
        <div id="page-container">
            <!-- Page Container -->
            <div id="content-wrap" class="w3-container w3-content">
                <!-- The Grid -->
                <div class="w3-row">

                    <!-- Left Column -->
                    <?php require_once(SHARED_PATH . 'left-column.php'); ?>
                    <!-- Middle Column -->
                    <main class="w3-col m7 w3-margin-bottom">

                        <div class="w3-row-padding">
                            <span id="sessionMsg"><?php echo $session->displaySessionMessage(); ?></span>
                            <div class="w3-col m12">
                                <div class="w3-card w3-round w3-theme-l4">
                                    <div class="w3-container w3-padding">
                                        <i class="fa fa-fw fa-address-book-o fa-2x card-body-icon w3-right"></i>
                                        <h4><a href="<?php echo Utility::urlFor('index.php'); ?>">Dashboard</a> / Users &rarr; <a class="small w3-theme-text" href="<?php echo Utility::urlFor('/staff/admins/add.php'); ?>">Add New</a></h4>
                                        <hr class="w3-clear">
                                        <table class="w3-table">
                                            <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">Image</th>
                                                    <th scope="col">Name</th>
                                                    <th class="w3-hide-small w3-hide-medium" scope="col">Email</th>
                                                    <th class="w3-hide-small w3-hide-medium" scope="col">Status</th>
                                                    <th scope="col">Joined</th>
                                                    <th class="w3-hide-small" scope="col">Role</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($entries as $entry) : ?>
                                                    <tr>
                                                        <th scope="row"><?php echo Admin::count(); ?></th>
                                                        <td><img class="w3-circle" src="<?php echo $entry->displayProfileImage(); ?>" alt="Profile" height="40" width="40"></td>
                                                        <td class="no-wrap"><?php echo $entry->fullName(true); # pass true for Last, First format               ?>
                                                            <div class="w3-clear"></div>

                                                            <span id="<?php echo Utility::h($entry->id); ?>" class="hidden small">
                                                                <?php echo $entry->displayAdminLinks('admins', $session); ?>
                                                            </span>

                                                        </td> 
                                                        <td class="w3-hide-small w3-hide-medium"><?php echo $entry->printEmail(); ?></td>
                                                        <td class="w3-hide-small w3-hide-medium"><?php echo Utility::h($entry->status); ?></td>
                                                        <td><?php echo Utility::formatDate($entry->date_created, 'm/d/Y'); ?></td>
                                                        <td class="w3-hide-small w3-hide-medium"><?php echo Utility::h($entry->role_name); ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                        <div class="mx-auto">
                                            <?php echo $pagination->pageLinks(Utility::urlFor('/staff/admins/index.php')); ?>
                                        </div>
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
        break;
endswitch;
require_once(MODAL_PATH . 'delete_modal.php');
require_once(SHARED_PATH . 'public_footer.php');
