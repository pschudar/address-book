<?php
require_once('../initialize.php');

$page_title = 'Unauthorized';

require_once(SHARED_PATH . 'public_header.php');
?>
<div class="col-md-4 mx-auto">

    <div class="card bg-light text-dark mb-3 mt-5">
        <div class="card-header"><span class="os os-exclamation-triangle text-danger"></span> Warning</div>
        <div class="card-body">
            <h5 class="card-title">Unauthorized</h5>
            <p class="card-text text-center">The requested action or operation requires a permissions elevation</p>
        </div>
    </div>
    <span id="sessionMsg" class="mt-5"><?php echo $session->displaySessionMessage(); ?></span>
</div>

<?php
require_once(SHARED_PATH . 'public_footer.php');

