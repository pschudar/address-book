<?php

use \utility\Utility,
    \user\Admin;
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="A free tool written in PHP that allows a person to maintain a contact or address book. Utilizes some HTML 5, Bootstrap 5 (alpha), jQuery and some JavaScript">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
        <link rel="stylesheet" href="<?php echo Utility::urlFor('/assets/css/contacts.styles.css'); ?>">
        <link href="<?php echo Utility::urlFor('/assets/fontello/css/open.source.icons.css'); ?>" rel="stylesheet" type="text/css">
        <link rel="apple-touch-icon" sizes="180x180" href="<?php echo Utility::urlFor('/assets/images/favicon/apple-touch-icon.png'); ?>">
        <link rel="icon" type="image/png" sizes="32x32" href="<?php echo Utility::urlFor('/assets/images/favicon/favicon-32x32.png'); ?>">
        <link rel="icon" type="image/png" sizes="16x16" href="<?php echo Utility::urlFor('/assets/images/favicon/favicon-16x16.png'); ?>">
        <link rel="manifest" href="<?php echo Utility::urlFor('/assets/images/favicon/site.webmanifest'); ?>">
        <title>
            <?php
            if (!Utility::hasPresence($page_title)) {
                $page_title = 'Address Book';
            }
            echo $page_title;
            ?>
        </title>
    </head>
    <body class='bg-light'>

        <header class="container mt-4">

                <nav class="navbar navbar-expand-lg navbar-white rounded shadow-lg">
                    <div class="container-fluid">
                        <a class="navbar-brand" href="<?php echo Utility::urlFor('index.php'); ?>">
                            <img class="mr-3 elevation-4 rounded-circle brand-image" src="<?php echo Utility::urlFor('/assets/images/site/address.png'); ?>" alt="Address Book Logo">
                        </a>
                        <div class="lh-100 w-25 d-flex-inline">
                            <h6 class="mb-0 lh-100">Address Book</h6>
                            <small class="d-flex flex-columnn">Version: <?php echo VERSION_NO; ?></small>
                        </div>
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#addressNav" aria-controls="addressNav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>

                        <div class="collapse navbar-collapse" id="addressNav">

                            <form action="search.php" class="d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0 navbar-search">
                                <div class="input-group">
                                    <input type="text" name="search" method="GET" class="form-control" placeholder="Search for..." aria-label="Search" aria-describedby="search-addon">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="button">
                                            <span class="os os-search os-sm"></span>
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <ul class="navbar-nav ml-auto ml-md-0 mb-2">

                                <li class="nav-item dropdown no-arrow mx-1">
                                    <a class="nav-link dropdown-toggle" href="#" id="addDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="os os-plus os-fw"></span>
                                        <span class="badge badge-primary"></span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="alertsDropdown">
                                        <a class="dropdown-item" href="<?php echo Utility::urlFor('/staff/contacts/add.php'); ?>"><span class="os os-plus os-sm os-fw mr-2"></span> New Contact</a>
                                        <a class="dropdown-item" href="<?php echo Utility::urlFor('/staff/admins/add.php'); ?>"><span class="os os-plus os-sm os-fw mr-2"></span> New Admin</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="#"><span class="os os-gears os-sm os-fw mr-2"></span> Settings</a>
                                    </div>
                                </li>

                                <li class="nav-item dropdown no-arrow">
                                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="mr-2 small"><?php echo $_SESSION['full_name']; ?></span> 
                                        <span class="os os-user-circle os-fw d-none d-lg-inline"></span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                        <a class="dropdown-item" href="<?php echo Utility::urlFor('/staff/admins/view.php?id=' . $_SESSION['admin_id']); ?>"><span class="os os-user os-sm os-fw mr-2"></span> Profile</a>
                                        <a class="dropdown-item" href="#"><span class="os os-gears os-sm os-fw mr-2"></span> Settings</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal"><span class="os os-logout os-sm os-fw mr-2 text-muted"></span> Logout</a>
                                    </div>
                                </li>

                            </ul>

                        </div>
                    </div>
                </nav>

        </header>
