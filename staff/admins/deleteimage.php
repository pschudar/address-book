<?php

use \file\UserImageXref,
    \utility\Utility;

require_once('../../includes/initialize.php');
require_once('../../includes/classes/file/UserImageXref.php'); # for some reason, the autoloader wouldnt load this
$session->requireLogin();
$getId = filter_input(INPUT_GET, 'id', 519); # filter as int

if (!isset($getId)) {
    Utility::redirectTo(Utility::urlFor('index.php'));
}

if (Utility::isPostRequest()) {
    # If user confirmed it - delete the existing image records
    $result = UserImageXref::removeProfileImage($getId);

    if ($result === true):
        $session->message('successfully deleted profile image');
        Utility::redirectTo(Utility::urlFor('/staff/'.THIS_DIR.'/edit.php?id=' . $getId));
    endif;
}
$page_title = 'Delete Profile Image';
require_once(SHARED_PATH . 'public_header.php');
?>

<!-- Fallback -->
<main role="main"> 
    <div class='container mt-4'>
        <div class="my-3 p-3 bg-white rounded shadow">
            <div class="col-md-4 mx-auto">
            <div class="card">
                <div class="card-header">
                    Delete Profile Image
                </div>
                <div class="card-body">
                    <h5 class="card-title">Are you sure?</h5>
                    <p class="card-text">This action is permanent and cannot be undone.</p>
                    <form id="deleteProfileImage" action="<?php echo Utility::urlFor('/staff/'.THIS_DIR.'/deleteimage.php?id=') . $getId; ?>" method="post">
                            <a role="button" href="<?php echo Utility::urlFor('/staff/'.THIS_DIR.'/edit.php?id=') . $getId; ?>" class="btn btn-outline-secondary">Cancel</a>
                            <input type="submit" class="btn btn-outline-danger" name="commit" value="Delete Image" />
                    </form>
                </div>
            </div>
            </div>
        </div>
    </div>
</main>
<!-- // Fallback -->

<?php
require_once(SHARED_PATH . 'public_footer.php');


