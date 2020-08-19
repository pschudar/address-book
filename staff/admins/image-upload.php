<?php

use \file\ThumbnailUpload,
    \file\ImageEntry,
    \file\UserImageXref,
    \utility\Utility;

require_once('../../includes/initialize.php');
require_once('../../includes/classes/file/UserImageXref.php'); # for some reason, the autoloader wouldnt load this
$session->requireLogin();

$post_upload = filter_input(INPUT_POST, 'upload', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

if (Utility::isPostRequest() && isset($post_upload)) {
    $getId = filter_input(INPUT_GET, 'id', 519);
    $ok = false;
    $loader = new ThumbnailUpload(PROFILE_IMAGE_PATH, true); # true marks deletion of orig img
    $loader->setMaxSize(2097152); # roughly 2MB's. inherited from Upload class
    $loader->setThumbSuffix($loader->genSuffix());
    $loader->upload();
    $messages = $loader->getMessages();
    $names = $loader->getFilenames();
    if (Utility::isBlank($names)) {
        $ok = false;
    } else {
        $ok = true;
        # pinpoint the name of the file being uploaded
        $i_arg['filename'] = $names[0];
        # instantiate the ImageEntry object
        $img = new ImageEntry($i_arg);
        # delete pre-existing image records and file, if it exists
        UserImageXref::removeProfileImage($getId);
        # Save the image - inherited from DatabaseObject class
        $img_result = $img->save();
        # if the ID exists, then we had a successful insert
        if (!empty($img->id)) {
            # add the user_id to the i_args array
            $i_args['user_id'] = $getId;
            # add the image_id to the i_args array
            $i_args['image_id'] = $img->id;
            # instantiate the object
            $img_xref = new UserImageXref($i_args);
            # save the object to the database
            $i_result = $img_xref->save();
        }
    }

}