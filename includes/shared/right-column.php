 <div class="w3-col m2">
            <div class="w3-card w3-round w3-white w3-center">
                <div class="w3-container">
                    <p class="w3-center"><i class="fa fa-plus"></i></p>
                    <!-- Still need to check if the user is authorized before showing these links -->
                    <p><a class="w3-button w3-block w3-theme-l3" href="<?php echo \utility\Utility::urlFor('/staff/contacts/add.php'); ?>">Add New Contact</a></p>
                    <p><a class="w3-button w3-block w3-theme-l4" href="<?php echo \utility\Utility::urlFor('/staff/admins/add.php'); ?>">Add New User</a></p>
                    <p><button class="w3-button w3-block w3-theme-l2" disabled>Add New Role</button></p>
                </div>
            </div>
            <br>

            <div class="w3-card w3-round w3-white w3-center">
                <div class="w3-container">
                    <p class="w3-center"><i class="fa fa-eye"></i></p>
                    <!-- Still need to check if the user is authorized before showing these links -->
                    <p><a class="w3-button w3-block w3-theme-l2" href="<?php echo \utility\Utility::urlFor('/staff/contacts/index.php'); ?>">View Contacts</a></p>
                    <p><a class="w3-button w3-block w3-theme-l4" href="<?php echo \utility\Utility::urlFor('/staff/admins/index.php'); ?>">View Users</a></p>
                    <p><a class="w3-button w3-block w3-theme-l3" href="<?php echo \utility\Utility::urlFor('/staff/roles/index.php'); ?>">View Roles</a></p>
                </div>
            </div>
            <br>
            <?php if(THIS_DIR === 'admins' && THIS_FILE === 'edit.php' || THIS_FILE === 'add.php') { ?>
            <div class="w3-card w3-round w3-white w3-center">
                <div class="w3-container">
                    <p class="w3-center w3-theme-d5">Password Tips</p>
                    <ul class="w3-ul w3-border w3-border-theme w3-margin-bottom">
                        <li id="pw_blank">May not be blank</li>
                        <li id="pw_length">5 characters min</li>
                        <li id="pw_lower">1 lowercase character</li>
                        <li id="pw_upper">1 uppercase character</li>
                        <li id="pw_symbol">1 symbol</li>
                        <li id="pw_number">1 number</li>
                        <li id="pw_match">Passwords match</li>
                    </ul>
                </div>
            </div>
            <br>
            <?php } else { ?>

                <!-- Built With --> 
    <div class="w3-card w3-round w3-white w3-hide-small">
        <div class="w3-container">
            <p>Built With</p>
            <p>
                <span class="w3-tag w3-small w3-theme-d5">HTML5</span>
                <span class="w3-tag w3-small w3-theme-d4">CSS3</span>
                <span class="w3-tag w3-small w3-theme-d3">PHP7</span>
                <span class="w3-tag w3-small w3-theme-d2">MariaDB/MySQL</span>
                <span class="w3-tag w3-small w3-theme-d1">W3.CSS</span>
                <span class="w3-tag w3-small w3-theme">JavaScript</span>
                <span class="w3-tag w3-small w3-theme-l3">GD Library</span>
                <span class="w3-tag w3-small w3-theme-l4">File API</span>
            </p>
        </div>
    </div>
    <br>

            <div class="w3-card w3-round w3-white w3-padding-32 w3-center w3-hide-medium w3-hide-large">
                <p><i class="fa fa-address-book-o w3-xxlarge"></i></p>
            </div>
            <?php } ?>

            <!-- End Right Column -->
        </div>