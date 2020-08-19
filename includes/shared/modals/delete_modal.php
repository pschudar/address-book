<!-- Delete Contact Modal -->
<div id="deleteContact" tabindex="-1" role="dialog" aria-labelledby="deleteContactLabel" aria-hidden="true" class="w3-modal w3-animate-opacity">
    <div class="w3-modal-content w3-card-4" role="document">
        <header class="w3-container w3-red"> 
            <span onclick="document.getElementById('deleteContact').style.display = 'none'" class="w3-button w3-large w3-display-topright">&times;</span>
            <h3><i class="fa fa-trash-o"></i> Delete: <span id="deleteContactName"></span></h3>
        </header>
        <div class="w3-container">
            <p class="w3-text-dark"><i class="fa fa-exclamation-triangle w3-text-danger"></i> This action is permanent and cannot be undone.</p>
            <p class="w3-text-dark">Continue?</p>
        </div>
        <footer class="w3-container w3-white">
            <form id="deleteContactForm" action="<?php echo \utility\Utility::urlFor('/staff/contacts/delete.php?id='); ?>" method="post">
                <input type="button" id="cancelDeleteContact" class="w3-button w3-round-large w3-white w3-border w3-border-grey" name="cancel" value="Cancel">
                <input type="submit" class="w3-button w3-round-large w3-white w3-border w3-border-red w3-ripple" name="commit" value="Delete" />
            </form>
        </footer>
    </div>
</div>