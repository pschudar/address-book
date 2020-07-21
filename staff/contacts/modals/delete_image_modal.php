<!-- Delete Profile Image Modal -->
<div class="modal fade" id="deleteProfileImage" tabindex="-1" role="dialog" aria-labelledby="deleteProfileImageLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header label-danger">
                <h5 class="modal-title" id="deleteAdminLabel"><span class="os os-trash-o"></span> Delete Profile Image?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body mx-auto">
                <span class="text-center"><span class="os os-exclamation-triangle text-danger"></span> This action is permanent and cannot be undone.</span>
                <?php if($entry->profile_image) { ?>
                <img width="20%" height="auto" class="img-fluid img-thumbnail rounded-circle mx-auto d-block" src="<?php echo \utility\Utility::urlFor('/assets/images/profile/') . \utility\Utility::u($entry->profile_image); ?>" alt="Profile Image">
                <?php } ?>
            </div>
            <div class="modal-footer">
                <button id="cancelDeleteProfileImage" type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                <form id="deleteProfileImageForm" action="<?php echo \utility\Utility::urlFor('/staff/contacts/deleteimage.php?id=') . '' . \utility\Utility::hu($entry->id); ?>" method="post"> <!--  -->
                        <input type="submit" class="btn btn-outline-danger" name="commit" value="Delete Image" />
                </form>
            </div>
        </div>
    </div>
</div>