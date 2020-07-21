<footer>
<!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header label-info">
                    <h5 class="modal-title" id="logoutModalLabel"><span class="os os-logout"></span> Logout</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">You will be logged out. Continue?</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="<?php echo \utility\Utility::urlFor('/staff/logout.php?do=logout'); ?>"><span class="os os-logout"></span> Logout</a>
                </div>
            </div>
        </div>
    </div>
<!-- JavaScript and dependencies -->
<script defer src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script defer src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>
<script defer src="<?php echo \utility\Utility::urlFor('/assets/js/script.js'); ?>"></script>
<script defer src="<?php echo \utility\Utility::urlFor('/assets/js/jquery-3.5.1.min.js'); ?>"></script>
<script defer src="<?php echo \utility\Utility::urlFor('/assets/js/bootstrap-filestyle.js'); ?>"></script>
<script defer src="<?php echo \utility\Utility::urlFor('/assets/js/bs.fs.buttonstyle.js'); ?>"></script>
</footer>
</body>
</html>
<?php ob_end_flush();
$database = databaseDisconnect($database);