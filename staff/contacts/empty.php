<?php

use \utility\Utility;

$add_link = Utility::urlFor('/staff/contacts/add.php');
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
                                <h4><a href="<?php echo Utility::urlFor('index.php'); ?>">Dashboard</a> / Contacts &rarr; <a class="small w3-theme-text" href="<?php echo Utility::urlFor('/staff/contacts/add.php'); ?>">Add New</a></h4>
                                <hr class="w3-clear">
                                <!-- Alert Box -->
                                <div class="w3-container w3-display-container w3-round w3-theme-d5 w3-border w3-theme-border w3-margin-bottom w3-hide-small">
                                    <span onclick="this.parentElement.style.display = 'none'" class="w3-button w3-theme-l3 w3-display-topright">
                                        <i class="fa fa-remove"></i>
                                    </span>
                                    <p><strong>0 Contacts</strong></p>
                                    <p>You can <a href="<?php echo $add_link; ?>">add a new contact here.</a></p>
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
