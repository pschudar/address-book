<!-- Add Profile Image Modal -->
<div id="addProfileImage" tabindex="-1" role="dialog" aria-labelledby="addProfileImageLabel" aria-hidden="true" class="w3-modal w3-animate-opacity">
    <div class="w3-modal-content w3-card-4" role="document">
        <header class="w3-container w3-theme"> 
            <span onclick="document.getElementById('addProfileImage').style.display = 'none'" class="w3-button w3-large w3-display-topright">&times;</span>
            <h3><i class="fa fa-cloud-upload"></i> Upload Profile Image</h3>
        </header>
        <div class="w3-container">

            <div class="w3-half">
                <div id="drop-area">
                                    <div id="gallery" class="w3-center"></div>
                                    <form role="form" class="drop-form w3-center" id="user-image-upload-form" action="<?php echo \utility\Utility::urlFor('/staff/'.THIS_DIR.'/image-upload.php?id=' . \utility\Utility::hu($getId)); ?>" method="post" style='display: block;' enctype="multipart/form-data">                

                                        <input type="hidden" name="MAX_FILE_SIZE" id="MAX_FILE_SIZE" value="2091752">
                                        <br>
                                        <input type='file' name="image" id="image" accept="image/*" onchange="handleFiles(this.files)">
                                        <label class="w3-btn w3-blue" for="image"><i class="fa fa-image"></i> Browse Images</label>
                                        <button id="reset" name="reset" type="reset" class="w3-btn w3-border w3-red w3-ripple"> Reset</button>
                                        <br>
                                        <input id="upload" type="hidden" name="upload" value="Upload Images">
                                        <button for="upload" id="submit" name="submit" type="submit" class="w3-btn w3-border w3-green"> Upload Image</button>
                                    </form>
                                    <div class="w3-center">
                                        <progress id="progress-bar" class="w3-theme-d4" max="100" value="0"></progress>
                                    </div>
                                </div>
            </div>
            <div class="w3-half">
                <div id="manageProfileModal" class="w3-right mx-auto card-body">
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
                    <br><img id="profileImageModalTag" class="img-fluid <?php echo $image_class; ?> w3-circle elevation-5 scale profile-image-modal" src="<?php echo \utility\Utility::urlFor('/assets/images/profile/') . '' . $entry->profile_image; ?>" alt="Profile Image">
                </div>
            </div>
        </div>
        <footer class="w3-container w3-white">
            <button id="cancelAddProfileImage" type="button" class="w3-btn w3-border w3-theme w3-right" data-dismiss="modal">Done</button>
            <div class="w3-clear">&nbsp;</div>
        </footer>
    </div>
</div>