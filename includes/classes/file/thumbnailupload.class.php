<?php

namespace file;

/**
 * ThumbnailUpload
 *
 * @category Thumbnail
 * @package File
 * @copyright Copyright (c) 2015
 * @license http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @version 0.7
 * @internal Created: 6.21.15 | Last Updated 06.7.20
 * */
class ThumbnailUpload extends \file\Upload {

    protected $_thumbDestination;
    protected $_deleteOriginal;
    protected $_suffix = 'PB';

    /**
     * Stores filename(s) in an array
     * @var string 
     */
    protected $_filenames = [];

    public function __construct($path, $deleteOriginal = false) {
        parent::__construct($path);
        $this->_thumbDestination = $path;
        $this->_deleteOriginal = $deleteOriginal;
    }

    public function setThumbDestination($path) {
        if (!is_dir($path) || !is_writable($path)) {
            throw new \Exception("$path must be a valid, writable directory.");
        }
        $this->_thumbDestination = $path;
    }

    public function genSuffix($max = 6) {
        $suffix = null;
        $charList = 'abcdefghijklmnopqrstuvwxyz_ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $i = 0;
        while ($i < $max) {
            $suffix .= $charList[mt_rand(0, (strlen($charList) - 1))];
            $i++;
        }
        return $suffix;
    }

    public function setThumbSuffix($suffix) {
        if (preg_match('/\w+/', $suffix)) {
            if (strpos($suffix, '_') !== 0) {
                $this->_suffix = '_' . $suffix;
            } else {
                $this->_suffix = $suffix;
            }
        } else {
            $this->_suffix = '';
        }
    }

    public function getThumbDestination() {
        return $this->_thumbDestination;
    }

    public function allowAllTypes($suffix = '') {
        $this->_typeCheckingOn = true;
    }

    /**
     *  Returns back the filenames
     *  Can be used by the calling code:
     * <code>$names = $loader->getFilenames();</code>
     * $names[0] is the first file $names[1] is the second, so on, and so forth.
     * @return string
     */
    public function getFilenames() {
        return $this->_filenames;
    }

    protected function createThumbnail($image) {
        $thumb = new \file\Thumbnail($image);
        $thumb->setDestination($this->_thumbDestination);
        $thumb->setSuffix($this->_suffix);
        $thumb->create();
        $messages = $thumb->getMessages();
        $this->_filenames = $thumb->getFileNames();
        $this->_messages = array_merge($this->_messages, $messages);
    }

    /**
     * Takes a file of type image/jpeg and reads exif orientation
     * data. Given that the orientation is set as 3,6, or 8, 
     * the method rotates the file appropriately so it is right-side up
     * https://stackoverflow.com/questions/3657023/how-to-detect-shot-angle-of-photo-and-auto-rotate-for-website-display-like-desk
     * This could be placed into class.upload.php as well but that doesn't deal specifically with images.
     * @param type $filename
     */
    private function correctOrientation($filename) {
        if (function_exists('exif_read_data')) :
            $exif = exif_read_data($filename);
            if ($exif && isset($exif['Orientation'])) {
                $orientation = $exif['Orientation'];
                if ($orientation != 1) {
                    $img = imagecreatefromjpeg($filename);
                    $deg = 0;
                    switch ($orientation) {
                        case 3:
                            $deg = 180;
                            break;
                        case 6:
                            $deg = -90;
                            break;
                        case 8:
                            $deg = 90;
                            break;
                    }
                    if ($deg) {
                        $img = imagerotate($img, $deg, 0);
                    }
                    # write the rotated image to the file system
                    imagejpeg($img, $filename);
                }
            }
        endif;
    }

    /**
     * Checks an images mime type. If it's an image/jpeg, returns true
     * @param type $file
     * @return boolean
     */
    protected function mimeTypeJPG($file) {
        $mimeType = image_type_to_mime_type(exif_imagetype($file));
        switch ($mimeType) {
            case 'image/jpeg':
            case 'image/pjpeg':
                return true;
            default:
                return false;
        }
    }

    protected function moveFile($file) {
        $filename = isset($this->_newName) ? $this->_newName : $file['name'];
        # Added this bit to gracefully show user a message if destination isn't a dir or writeable
        if (!is_dir($this->_destination) || !is_writable($this->_destination)) {
            $success = false;
        } else {
            $success = move_uploaded_file($file['tmp_name'], $this->_destination . $filename);
            # ONLY if it's a jpg or jpeg image
            if ($this->mimeTypeJPG($this->_destination . $filename)) {
                $success .= $this->correctOrientation($this->_destination . $filename);
            }
        }
        switch ($success) {
            default:
                # add the amended filename to the array of uploaded files
                $this->_filenames[] = $filename;
                # add a message only if the original image is not deleted
                if (!$this->_deleteOriginal) :
                    $result = '1' . $file['name'] . ' was uploaded successfully';
                    if (!is_null($this->_newName)) :
                        $result .= ' and renamed ' . $this->_newName;
                    endif;
                    $this->_messages[] = $result;
                endif;
                # create a thumbnail from the uploaded image
                $this->createThumbnail($this->_destination . $filename);
                # delete the uploaded image if required
                if ($this->_deleteOriginal) :
                    unlink($this->_destination . $filename);
                endif;
                break;
            case false:
                $this->_messages[] = '4 Could not upload ' . $file['name'];
                break;
        }
    }

}
