<?php
require_once('../initialize.php');

$page_title = 'Unauthorized';

require_once(SHARED_PATH . 'public_header.php');
?>

<div id="page-container">
    <!-- Page Container -->
    <div id="content-wrap" class="w3-container w3-content">
        <!-- The Grid -->
        <div class="w3-row">

            <!-- Left Column -->
            <?php require_once(SHARED_PATH . 'left-column.php'); ?>
            <!-- Middle Column -->
            <main class="w3-col m7">

                <div class="w3-row-padding w3-margin-bottom">
                    <span id="sessionMsg"><?php echo $session->displaySessionMessage(); ?></span>
                    <div class="w3-col m12">
                        <div class="w3-card w3-round w3-theme-l4">
                            <div class="w3-container w3-padding">
                                <i class="fa fa-fw fa-vcard-o fa-2x w3-right w3-text-theme w3-hide-small w3-hide-medium"></i>
                                <h4 class="w3-hide-small w3-hide-medium"><i class="fa fa-exclamation-triangle w3-text-danger"></i> Unauthorized</h4>
                                <hr class="w3-clear w3-hide-small w3-hide-medium">

                                <article>
                                    <ul class="w3-ul w3-large w3-left">
                                        <li>The requested action or operation requires a permissions elevation</li>
                                    </ul>
                                </article>
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

    