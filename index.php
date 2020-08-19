<?php

use \utility\Utility,
    \user\Admin;

require_once('includes/initialize.php');

$session->requireLogin();

if (!$session->hasPermission($_SESSION['role_permissions'], 1)) {
    Utility::accessDenied();
}

$page_title = 'Address Book: Dashboard';

$c = Admin::countDashboardItems();
# Select the stored entry
$user = Admin::findByUserId($_SESSION['admin_id']);
require_once(SHARED_PATH . 'public_header.php');
?>

<div id="page-container">
    <!-- Page Container -->
    <div id="content-wrap" class="w3-container w3-content" style="max-width:1400px;margin-top:80px">
        <!-- The Grid -->
        <div class="w3-row">

            <!-- Left Column -->
            <?php require_once(SHARED_PATH . 'left-column.php'); ?>
            <!-- Middle Column -->
            <div class="w3-col m7">

                <div class="w3-row-padding">

                    <div class="w3-col m4 w3-margin-bottom">
                        <div class="w3-card w3-round w3-theme-l4">
                            <div class="w3-container w3-padding">
                                <i class="fa fa-fw fa-address-book-o fa-2x card-body-icon w3-right w3-xxlarge"></i>
                                <h4 class="mt-0"><?php echo "$c->contact_count"; ?> Contacts</h4>
                                <hr class="w3-clear">
                                <a href="<?php echo Utility::urlFor('/staff/contacts/index.php'); ?>" class="w3-button w3-theme-d3 w3-round-large"><i class="fa fa-eye"></i> &nbsp; View</a>
                                <a href="<?php echo Utility::urlFor('/staff/contacts/add.php'); ?>" class="w3-button w3-theme-l5 w3-round-large"><i class="fa fa-plus"></i> &nbsp; Add</a>
                            </div>
                        </div>
                    </div>

                    <div class="w3-col m4 w3-margin-bottom">
                        <div class="w3-card w3-round w3-theme-l3">
                            <div class="w3-container w3-padding">
                                <i class="fa fa-fw fa-users fa-2x card-body-icon w3-right w3-xxlarge"></i>
                                <h4 class="mt-0"><?php echo "$c->user_count"; ?> Users</h4>
                                <hr class="w3-clear">
                                <a href="<?php echo Utility::urlFor('/staff/admins/index.php'); ?>" class="w3-button w3-theme-d5 w3-round-large"><i class="fa fa-eye"></i> &nbsp; View</a>
                                <a href="<?php echo Utility::urlFor('/staff/admins/add.php'); ?>" class="w3-button w3-theme-l2 w3-round-large"><i class="fa fa-plus"></i> &nbsp; Add</a>
                            </div>
                        </div>
                    </div>

                    <div class="w3-col m4 w3-margin-bottom">
                        <div class="w3-card w3-round w3-theme-l2">
                            <div class="w3-container w3-padding">
                                <i class="fa fa-fw fa-tasks fa-2x card-body-icon w3-right w3-xxlarge"></i>
                                <h4 class="mt-0"><?php echo "$c->role_count"; ?> Roles</h4>
                                <hr class="w3-clear">
                                <a href="<?php echo Utility::urlFor('/staff/roles/index.php'); ?>" class="w3-button w3-block w3-theme-d4 w3-round-large"><i class="fa fa-eye"></i> &nbsp; View</a>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- End Middle Column -->
            </div>

            <!-- Right Column -->
            <?php require_once(SHARED_PATH . 'right-column.php'); ?>

            <!-- End Grid -->
        </div>

        <!-- End Page Container -->
    </div>
    <?php require_once(SHARED_PATH . 'public_footer.php'); ?>
</div>
