<!-- Delete Profile Image Modal -->
<div id="deleteProfileImage" tabindex="-1" role="dialog" aria-labelledby="deleteProfileImageLabel" aria-hidden="true" class="w3-modal w3-animate-opacity">
    <div class="w3-modal-content w3-card-4" role="document">
        <header class="w3-container w3-red"> 
            <span onclick="document.getElementById('deleteProfileImage').style.display = 'none'" class="w3-button w3-large w3-display-topright">&times;</span>
            <h3><i class="fa fa-trash-o"></i> Delete Profile Image?</h3>
        </header>
        <div class="w3-container">
            <p class="w3-text-dark"><i class="fa fa-exclamation-triangle w3-text-danger"></i> This action is permanent and cannot be undone.</p>
            <p class="w3-text-dark">Continue?</p>
            <p class="w3-center">
                <?php if($entry->profile_image) { ?>
                <img width="10%" height="auto" class="w3-circle scale" src="<?php echo \utility\Utility::urlFor('/assets/images/profile/') . \utility\Utility::u($entry->profile_image); ?>" alt="Profile Image">
                <?php } ?>
            </p>
        </div>
        <footer class="w3-container w3-white">
            <form id="deleteContactForm" action="<?php echo \utility\Utility::urlFor('/staff/'.THIS_DIR.'/deleteimage.php?id=') . '' . \utility\Utility::hu($entry->id); ?>" method="post">
                <input type="button" id="cancelDeleteProfileImage" class="w3-button w3-round-large w3-white w3-border w3-border-grey" name="cancel" value="Cancel">
                <input type="submit" class="w3-button w3-round-large w3-white w3-border w3-border-red w3-ripple" name="commit" value="Delete Image" />
            </form>
        </footer>
    </div>
</div>