<!-- Add Profile Image Modal -->
<div id="addProfileImage" tabindex="-1" role="dialog" aria-labelledby="addProfileImageLabel" aria-hidden="true" class="w3-modal w3-animate-opacity">
    <div class="w3-modal-content w3-card-4" role="document">
        <header class="w3-container w3-theme"> 
            <span onclick="document.getElementById('addProfileImage').style.display = 'none'" class="w3-button w3-large w3-display-topright">&times;</span>
            <h3><i class="fa fa-cloud-upload"></i> Upload Profile Image</h3>
        </header>
        <div class="w3-container">
            <ul id="msgList" class="w3-ul w3-border">
                <li id="selImg" class="w3-text-info"><span class="fa fa-info-circle"> <span class="w3-text-dark">Upload an image</span></span></li>
            </ul>
            <div class="w3-third">
                <form role="form" class="form-horizontal" id="user-image-upload-form" action="<?php echo \utility\Utility::urlFor('/staff/contacts/image-upload.php?id=' . \utility\Utility::hu($getId)); ?>" method="post" style='display: block;' enctype="multipart/form-data">                

                    <input type="hidden" name="MAX_FILE_SIZE" id="MAX_FILE_SIZE" value="2091752">
                    <br>
                    <input type='file' class='filestyle' data-input="false" name="image" id="image" accept="image/*">
                    <br>
                    <input id="upload" type="hidden" name="upload" value="Upload Images">
                    <button for="upload" id="submit" name="submit" type="submit" class="w3-btn w3-border w3-green"> Upload Image</button> 
                    <button id="reset" name="reset" type="reset" class="w3-btn w3-border w3-red"> Reset</button>
                </form>
            </div>
            <div class="w3-third">
                <div id="manageProfileModal" class="w3-center card-body">
                    <?php
                    $image_class = null;
                    switch (\utility\Utility::isBlank($entry->profile_image)) {
                        case true:
                            # if profile_image is not yet set, apply a default image
                            $entry->profile_image = 'default.png';
                            $image_class = null;
                            break;
                        default:
                            # if profile_image exists, define a class for it. The default image looks weird w/this class.
                            $image_class = 'img-thumbnail';
                            break;
                    }
                    ?>
                    <img width="40%" height="auto" class="img-fluid <?php echo $image_class; ?> w3-circle mx-auto scale" src="<?php echo \utility\Utility::urlFor('/assets/images/profile/') . '' . $entry->profile_image; ?>" alt="Profile Image">
                </div>
            </div>
            <div class="w3-rest">

                <div class="<?php echo $status_class_set; ?>" id="validationRules">
                    <div id="validationStatus" class="card-body">
                        <?php
                        #echo display_errors($admin->errors);
                        #echo "<p>&#8646;</p>";
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <footer class="w3-container w3-white">
            <button id="cancelAddProfileImage" type="button" class="w3-btn w3-border w3-theme w3-right" data-dismiss="modal">Done</button>
            <div class="w3-clear">&nbsp;</div>
        </footer>
    </div>
</div>