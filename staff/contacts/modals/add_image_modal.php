<!-- Add Profile Image Modal -->
<div class="modal fade" id="addProfileImage" tabindex="-1" role="dialog" aria-labelledby="addProfileImageLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header label-primary">
                <h5 class="modal-title" id="addAdminLabel"><span class="os os-upload-cloud"></span> Add a Profile Image</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                
                <div id="uploadCard" class="card shadow">
            <div class="card-body">
                <ul id="msgList" class="list-group">
                    <li id="selImg" class="list-group-item label-info"><span class="os os-info-circle"> Upload an image</span></li>
                </ul>
                <?php echo $session->displaySessionMessage(); ?>
                <div class="row no-gutters">
                    <div class="col-md-3">
                        <form role="form" class="form-horizontal" id="user-image-upload-form" action="<?php echo \utility\Utility::urlFor('/staff/contacts/image-upload.php?id=' . \utility\Utility::hu($getId)); ?>" method="post" style='display: block;' enctype="multipart/form-data">                

                            <input type="hidden" name="MAX_FILE_SIZE" id="MAX_FILE_SIZE" value="2091752">
                            <br>
                            <input type='file' class='filestyle' data-input="false" name="image" id="image" accept="image/*">
                            <br>
                            <input id="upload" type="hidden" name="upload" value="Upload Images">
                            <button for="upload" id="submit" name="submit" type="submit" class=" btn btn-success"> Upload Image</button> 
                            <button id="reset" name="reset" type="reset" class=" btn btn-danger"> Reset</button>
                        </form>
                    </div>
                    <div class="col-md-4">
                        <div id="manageProfileModal" class="card-body mx-auto">
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
                            <img width="40%" height="auto" class="img-fluid <?php echo $image_class; ?> rounded-circle mx-auto d-block scale" src="<?php echo \utility\Utility::urlFor('/assets/images/profile/') . '' . $entry->profile_image; ?>" alt="Profile Image">
                        </div>
                    </div>
                    <div class="col-md-5 mb-2">

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
            </div>
        </div>
                
            </div>
            <div class="modal-footer">
                <button id="cancelAddProfileImage" type="button" class="btn btn-outline-primary" data-dismiss="modal">Done</button>
            </div>
        </div>
    </div>
</div>