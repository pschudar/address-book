<?php

use \utility\Utility,
    \user\Admin;

require_once('includes/initialize.php');

$session->requireLogin();

if (!$session->hasPermission($_SESSION['role_permissions'], 1)) {
    Utility::redirectTo(Utility::urlFor('includes/shared/access_denied.php'));
}

$page_title = 'Address Book: Dashboard';
require_once(SHARED_PATH . 'public_header.php');

$c = Admin::countDashboardItems();
# Select the stored entry
$user = Admin::findByUserId($_SESSION['admin_id']);
?>
<main role="main"> 
    <div class='container mt-4'>
        <div class="my-3 p-3 bg-white rounded shadow">
            <!-- <img height="20%" width="auto" class="card-img-top filter-gray" src="<?php echo Utility::urlFor('/assets/images/site/header.png'); ?>" alt="Background"> -->
            <span id="profileImage">
                <img class="img-fluid img-thumbnail rounded-circle d-block scale avatar mx-auto elevation-5" src="<?php echo $user->displayProfileImage(); ?>" alt="Profile">
            </span>
            <span id="sessionMsg"><?php echo $session->displaySessionMessage(); ?></span>
            <div class="row">

                <div class="col-xl-3 col-sm-6 mb-3">
                    <div class="card text-white bg-primary o-hidden h-100">
                        <div class="card-body">
                            <div class="card-body-icon">
                                <i class="os os-fw os-address-card-o"></i>
                            </div>
                            <div class="mr-5"><?php echo "$c->contact_count"; ?> Contacts</div>
                        </div>
                        <a class="card-footer text-white clearfix small z-1" href="<?php echo Utility::urlFor('/staff/contacts/index.php'); ?>">
                            <span class="float-left">View Details</span>
                            <span class="float-right">
                                <span class="os os-arrow-circle-right"></span>
                            </span>
                        </a>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 mb-3">
                    <div class="card text-white bg-secondary o-hidden h-100">
                        <div class="card-body">
                            <div class="card-body-icon">
                                <i class="os os-fw os-id-card-o"></i>
                            </div>
                            <div class="mr-5">My Profile</div>
                        </div>
                        <a class="card-footer text-white clearfix small z-1" href="<?php echo Utility::urlFor('/staff/admins/view.php?id=' . $_SESSION['admin_id']) ?>">
                            <span class="float-left">View Details</span>
                            <span class="float-right">
                                <span class="os os-arrow-circle-right"></span>
                            </span>
                        </a>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 mb-3">
                    <div class="card text-white bg-dark o-hidden h-100">
                        <div class="card-body">
                            <div class="card-body-icon">
                                <i class="os os-fw os-users"></i>
                            </div>
                            <div class="mr-5"><?php echo $c->user_count; ?> Registered Users</div>
                        </div>
                        <a class="card-footer text-white clearfix small z-1" href="<?php echo Utility::urlFor('/staff/admins/index.php'); ?>">
                            <span class="float-left">View Details</span>
                            <span class="float-right">
                                <span class="os os-arrow-circle-right"></span>
                            </span>
                        </a>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 mb-3">
                    <div class="card text-white bg-info o-hidden h-100">
                        <div class="card-body">
                            <div class="card-body-icon">
                                <i class="os os-fw os-tasks"></i>
                            </div>
                            <div class="mr-5"><?php echo "$c->role_count"; ?> User Roles</div>
                        </div>
                        <a class="card-footer text-white clearfix small z-1" href="<?php echo Utility::urlFor('/staff/roles/index.php'); ?>">
                            <span class="float-left">View Details</span>
                            <span class="float-right">
                                <span class="os os-arrow-circle-right"></span>
                            </span>
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </div>
</main>

<?php
require_once(SHARED_PATH . 'public_footer.php');
