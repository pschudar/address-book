<?php

use \user\Role,
    \utility\Utility,
    \paginate\Pagination;

require_once('../../includes/initialize.php');

# enforce authentication
$session->requireLogin();

# enforce permissions

/**
 * Site Permissions - Quick Notes
 * 
 * 1: View Contacts     5: View Admins
 * 2: Create Contacts   6: Create Admins
 * 3: Update Contacts   7: Update Admins
 * 4: Delete Contacts   8: Delete Admins
 */
if (!$session->hasPermission($_SESSION['role_permissions'], 9)) {
    Utility::accessDenied();
}

$page_title = 'Address Book: View Roles';
require_once(SHARED_PATH . 'public_header.php');

# define get variable 'page'
$page = filter_input(INPUT_GET, 'page', 519);
# define current page
$current_page = $page ?? 1; # If page is not set, page is defaults to 1
# count records
$total_count = Role::countAll();
# per page
$per_page = 10;
# instantiate paginate
$pagination = new Pagination($current_page, $per_page, $total_count);
$roles = Role::getAll($per_page, $pagination->offset());
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
                                <i class="fa fa-fw fa-tasks fa-2x card-body-icon w3-right"></i>
                                <h4><a href="<?php echo Utility::urlFor('index.php'); ?>">Dashboard</a> / Contacts &rarr; <a class="small w3-theme-text" href="<?php echo Utility::urlFor('/staff/contacts/add.php'); ?>">Add New</a></h4>
                                <hr class="w3-clear">
                                <table class="w3-table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Role Name</th>
                                            <th scope="col">Permissions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($roles as $role) : ?>
                                            <tr>
                                                <td><?php echo $role->role_name; ?>
                                                    <div class="clearfix"></div>

                                                    <span id="<?php echo Utility::h($role->role_id); ?>" class="hidden small">
                                                        <?php #echo $role->displayAdminLinks('roles', $session);   ?>
                                                    </span>

                                                </td>
                                                <td>
                                                    <div class="w3-row">
                                                        <?php
                                                        $card_classes = 'w3-theme-text'; # card bg-dark text-white
                                                        echo "<div class='w3-third'>\r\n";
                                                        echo "<div class='{$card_classes}'>\r\n";
                                                        foreach ($role->getRolePermissions($role->role_id) AS $r) {
                                                            $permCount = \user\Admin::count();
                                                            echo '' . $r->perm_desc . "<br>\r\n";
                                                            if ($permCount % 4 == 0) {
                                                                echo "</div>\r\n</div>\r\n
                                    <div class='w3-third'>\r\n
                                            <div class='{$card_classes}'>";
                                                            }
                                                        }
                                                        echo "</div>\r\n</div>\r\n";
                                                        ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <div class="mx-auto">
                                    <?php echo $pagination->pageLinks(Utility::urlFor('/staff/contacts/index.php')); ?>
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
    require_once(SHARED_PATH . 'public_footer.php');
    