<?php

use \utility\Utility,
    \user\Admin;

$user = Admin::findByUserId($_SESSION['admin_id']);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="A free tool written in PHP that allows a person to  build and maintain an address book. Utilizes HTML5, W3.CSS Framework, jQuery and some JavaScript">
        <link rel="stylesheet" href="<?php echo Utility::urlFor('/assets/css/styles.css'); ?>">
        <link rel="stylesheet" href="<?php echo Utility::urlFor('/assets/css/address-book.css'); ?>">
        <link id="theme-style" rel="stylesheet" href="<?php echo Utility::urlFor('/assets/css/themes/blue-grey.css'); ?>">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="apple-touch-icon" sizes="180x180" href="<?php echo Utility::urlFor('/assets/images/favicon/apple-touch-icon.png'); ?>">
        <link rel="icon" type="image/png" sizes="32x32" href="<?php echo Utility::urlFor('/assets/images/favicon/favicon-32x32.png'); ?>">
        <link rel="icon" type="image/png" sizes="16x16" href="<?php echo Utility::urlFor('/assets/images/favicon/favicon-16x16.png'); ?>">
        <link rel="manifest" href="<?php echo Utility::urlFor('/assets/images/favicon/site.webmanifest'); ?>">
        <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Open+Sans'>
        <title>
            <?php
            if (!Utility::hasPresence($page_title)) {
                $page_title = 'Address Book';
            }
            echo $page_title;
            ?>
        </title>
        <script>
            if (localStorage.getItem('stylePreference') !== null) {
                var stylePref = localStorage.getItem('stylePreference');
            } else {
                stylePref = "<?php echo Utility::urlFor('/assets/css/themes/blue-grey.css'); ?>";
            }
            document.getElementById('theme-style').href = stylePref;
        </script>
    </head>
    <body id="site-mode" class="w3-theme-light">

        <!-- Navbar -->
        <header>
            <nav>
                <div class="w3-top">
                    <div class="w3-bar w3-theme-d2 w3-left-align w3-large">
                        <a class="w3-bar-item w3-button w3-hide-medium w3-hide-large w3-right w3-padding-large w3-hover-white w3-large w3-theme-d2" href="javascript:void(0);" onclick="openNav()"><i class="fa fa-bars"></i></a>
                        <a href="<?php echo Utility::urlFor('index.php'); ?>" class="w3-bar-item w3-button w3-hide-small w3-left w3-padding-large w3-hover-white" title="Logo">
                            <img src="<?php echo Utility::urlFor('/assets/images/site/address.png'); ?>" class="w3-circle elevation-3" style="height:23px;width:23px" alt="Avatar">
                        </a>
                        <a href="<?php echo Utility::urlFor('index.php'); ?>" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white" title="Dashboard"><i class="fa fa-tachometer"></i></a>
                        <a href="<?php echo Utility::urlFor('/staff/contacts/index.php'); ?>" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white" title="Contacts"><i class="fa fa-address-book-o"></i></a>
                        <a href="<?php echo Utility::urlFor('/staff/admins/index.php'); ?>" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white" title="Users"><i class="fa fa-users"></i></a>
                        <a href="<?php echo Utility::urlFor('/staff/roles/index.php'); ?>" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white" title="Roles"><i class="fa fa-tasks"></i></a>
                        <a id="launchLogoutModal" href="#" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white" title="Logout"><i class="fa fa-sign-out"></i></a>

                        <a href="<?php echo Utility::urlFor('/staff/admins/view.php?id=' . $_SESSION['admin_id']) ?>"" class="w3-bar-item w3-button w3-hide-small w3-right w3-padding-large w3-hover-white" title="My Profile">
                            <img src="<?php echo $user->displayProfileImage(); ?>" class="w3-circle scale" style="height:23px;width:23px" alt="Avatar">
                        </a>
                    </div>
                </div>

                <!-- Navbar on small screens -->
                <div id="smallNav" class="w3-hide w3-hide-large w3-hide-medium w3-top w3-theme" style="margin-top:51px">
                    <ul class="w3-navbar w3-left-align w3-large w3-theme list-unstyled">
                        <li><a class="w3-padding-large" title="Dashboard" href="<?php echo Utility::urlFor('index.php'); ?>"> Dashboard</a></li>
                        <li><a class="w3-padding-large" title="Contacts" href="<?php echo Utility::urlFor('/staff/contacts/index.php'); ?>">Contacts</a></li>
                        <li><a class="w3-padding-large" title="Users" href="<?php echo Utility::urlFor('/staff/admins/index.php'); ?>">Users</a></li>
                        <li><a class="w3-padding-large" title="Roles" href="<?php echo Utility::urlFor('/staff/roles/index.php'); ?>">Roles</a></li>
                        <li><a id="launchLogoutModalSmall" href="#" class="w3-padding-large" title="Logout">Logout</a></li>
                    </ul>
                </div>
            </nav>
        </header>
