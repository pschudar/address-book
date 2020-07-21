<?php
use \utility\Utility;
$add_link = Utility::urlFor('/staff/contacts/add.php');
?>
<main role="main"> 
    <div class='container mt-4'>
        <div class="my-3 p-3 bg-white rounded shadow">
           <!-- <img class="card-img-top filter-gray" src="<?php echo Utility::urlFor('/assets/images/site/header.png'); ?>" alt="Background"> -->
            <span id="sessionMsg"><?php echo $session->displaySessionMessage(); ?></span>

            <div class="alert alert-secondary mt-5 mb-5" role="alert">
                There are no contacts stored yet. You can <span class="os os-plus os-fw text-muted"></span> <a class="alert-link" href="<?php echo $add_link; ?>">add contacts here</a>
            </div>

            <div class="mx-auto">
                <?php echo $pagination->pageLinks(Utility::urlFor('/staff/contacts/index.php')); ?>
            </div>
        </div>
    </div>
</main>