<!-- Delete Contact Modal -->
<div class="modal fade" id="deleteContact" tabindex="-1" role="dialog" aria-labelledby="deleteContactLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header label-danger">
                <h5 class="modal-title" id="deleteAdminLabel"><span class="os os-trash-o"></span> Delete: <span id="deleteContactName"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <span class="d-block ml-auto"><span class="os os-exclamation-triangle text-danger"></span> This action is permanent and cannot be undone.</span>
            </div>
            <div class="modal-footer">
                <button id="cancelDeleteContact" type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                <form id="deleteContactForm" action="<?php echo \utility\Utility::urlFor('/staff/contacts/delete.php?id='); ?>" method="post">
                        <input type="submit" class="btn btn-outline-danger" name="commit" value="Delete" />
                </form>
            </div>
        </div>
    </div>
</div>