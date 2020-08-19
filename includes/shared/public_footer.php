<!-- Logout Modal-->
<div id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="deleteContactLabel" aria-hidden="true" class="w3-modal w3-animate-opacity">
    <div class="w3-modal-content w3-card-4" role="document">
        <header class="w3-container w3-light-blue"> 
            <span onclick="document.getElementById('logoutModal').style.display = 'none'" class="w3-button w3-large w3-display-topright">&times;</span>
            <h3><span class="fa fa-sign-out"></span> Logout</h3>
        </header>
        <div class="w3-container">
            <p class="w3-text-dark"><i class="fa fa-info-circle w3-text-info"></i> You will be logged out.</p>
            <p class="w3-text-dark">Continue?</p>
        </div>
        <footer class="w3-container w3-white">
            <p>
                <a id="cancelLogout" href="#" class="w3-button w3-round-large w3-white w3-border w3-border-grey"><i class="fa fa-times"></i> &nbsp; Cancel</a>
                <a href="<?php echo \utility\Utility::urlFor('/staff/logout.php?do=logout'); ?>" class="w3-button w3-round-large w3-white w3-border w3-border-blue"><i class="fa fa-sign-out"></i> &nbsp; Logout</a>
            </p>
        </footer>
    </div>
</div>

<br>
<!-- Theme Switcher -->  
<?php if (THIS_FILE !== 'login.php') {
    require_once(SHARED_PATH . 'theme-switcher.php');
} ?>
<!-- Footer -->
<footer id="footer" class="w3-container w3-theme-d2 w3-center">
    <p><small>PHP Address Book <?php echo VERSION_NO; ?></small></p>
</footer>
</div> <!-- closes page-container --> 

<!-- JavaScript and dependencies -->
<script defer src="<?php echo \utility\Utility::urlFor('/assets/js/script.js'); ?>"></script>
<?php if (THIS_FILE === 'edit.php') { ?>
    <script defer src="<?php echo \utility\Utility::urlFor('/assets/js/drag.and.drop.js'); ?>"></script>
<?php } ?>
<!-- Style Switcher --> 
<script defer src="<?php echo \utility\Utility::urlFor('/assets/js/style.switcher.js'); ?>"></script>

</body>
</html> 
<?php
ob_end_flush();
$database = databaseDisconnect($database);
