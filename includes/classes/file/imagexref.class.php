<?php

namespace file;

/**
 * ImageXref creates, reads, updates, and deletes Image Cross References.
 * 
 * Uses the infrastructure from DatabaseObject class to perform CRUD actions
 */
class ImageXref extends \database\DatabaseObject {

    static protected $table_name = 'ab_image_xref';
    static protected $db_columns = ['contact_id', 'image_id'];
    public $contact_id;
    public $image_id;
    public $filename;

    public function __construct($args = []) {
        $this->contact_id = $args['contact_id'] ?? '';
        $this->image_id = $args['image_id'] ?? '';
    }
    /**
     * Deletes a user's profile image, if it exists.
     * @param int $contactId
     * @return boolean
     */
    public static function removeProfileImage(int $contactId) {
        $current = ImageXref::getCurrentImage($contactId);
        # check for a pre-existing image
        switch ($current) {
            case false:
                # there is no stored image, continue on.
                return false;
            default:
                # there is a stored image, delete it.
                $img = ImageXref::removeImageRecords($current->contact_id, $current->image_id, PROFILE_IMAGE_PATH, $current->filename);
                if ($img):
                    return true;
                else:
                    return false;
            endif;
        }
    }

    /**
     * Checks the mime type of a file to ensure it's an image
     * 
     * Of course, this isn't fool proof, but it's a good starting point
     * to ensure only images are being worked with.
     * 
     * @param string $imgPath
     * @param string $filename
     * @return boolean
     */
    private static function checkFile(string $imgPath, string $filename) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $imgPath . $filename);
        switch (strpos($mime_type, 'image/') !== false) {
            case true:
                # the mime type says it's an image file
                return true;
            case false:
                # the mime type says it's not an image file
                return false;
        }
    }

    /**
     * Checks that the directory exists and is formatted correctly. 
     * 
     * Checks that it contains the proper trailing slashes
     * Assigns the processed directory path / name to $this_dir and returns it. 
     * 
     * @param string $dir
     * @return string
     */
    private static function setDir(string $dir) {
        if (is_dir($dir)) {
            # get last character
            $last = substr($dir, -1);
            # add a trailing slash if missing
            if ($last == '/' || $last == '\\') {
                $this_dir = $dir;
            } else {
                $this_dir = $dir . DIRECTORY_SEPARATOR;
            }
        }
        return $this_dir;
    }

    /**
     * Removes Profile Image records from the database
     * 
     * Using one delete statement, it deletes the cross reference record first, 
     * since it is the child table of images. Then the image record from the 
     * images table is removed.
     * 
     * @param int $contactId
     * @param int $imageId
     * @return array
     */
    private static function removeImageRecords(int $contactId, int $imageId, string $imgPath, string $filename) {
        $sql = 'DELETE cix, img FROM ab_image_xref cix, ab_images img WHERE cix.contact_id = ' . $contactId . ' AND img.id = ' . $imageId;
        $path = self::setDir($imgPath);
        # if mime type check says it's an image, delete it
        if (self::checkFile($path, $filename)) {
            unlink("$path$filename");
        }
        $result = self::$database->query($sql);
        return $result;
    }

    /**
     * Selects a users current image_id, if it exists, in the database
     * 
     * @param int $contactId
     * @return array || boolean
     */
    private static function getCurrentImage(int $contactId) {
        $sql = 'SELECT cix.contact_id, cix.image_id, i.filename FROM ab_image_xref cix LEFT JOIN ab_images i on cix.image_id = i.id WHERE cix.contact_id = ' . $contactId . ' LIMIT 1';
        $obj_array = static::findBySql($sql);
        if (!empty($obj_array)):
            return array_shift($obj_array);
        else:
            return false;
        endif;
    }

}
