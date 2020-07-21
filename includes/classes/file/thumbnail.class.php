<?php

namespace file;

/**
 * Thumbnail
 *
 * @category Generate Thumbnail
 * @author David Powers
 * @internal Edited by Paul Schudar
 * @copyright Copyright (c) 2015
 * @license http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @version 0.7
 * @internal Created: 6.21.15 | Last Updated 7.18.15
 * */
class Thumbnail {

    /**
     * As a result of getimagesize(), the image's path is stored here.
     * 
     * @var string
     */
    protected $_original;

    /**
     * As a result of getimagesize(), the images width is stored here.
     * 
     * @var int
     */
    protected $_originalwidth;

    /**
     * As a result of getimagesize(), the images height is stored here.
     * 
     * @var int
     */
    protected $_originalheight;

    /**
     * The images name minus the extension is extracted using pathinfo() with
     * the PATHINFO_FILENAME constant and is stored in _basename which will 
     * be used to build the thumbnail's name w/the suffix.
     * 
     * @var string
     */
    protected $_basename;
    protected $_thumbwidth;
    protected $_thumbheight;

    /**
     * This property has been given a default value of 375 pixels.
     * This determines the max size of the resized images longer dimension
     * 
     * @var int
     */
    protected $_maxSize = 350;

    /**
     * Initialized with false, this prevents the script from attempting to 
     * process a file that isn't an image. The value will be reset to true if
     * the MIME type matches that of an image. It can also be used to prevent
     * the generation of a thumbnail if another error occurs.
     * 
     * @var bool
     */
    protected $_canProcess = false;

    /**
     * All image MIME types begin with image/. substr() extracts the characters 
     * after the slash and stores them in this property.
     * 
     * @var string
     */
    protected $_imageType;
    protected $_destination;
    protected $_suffix = 'pb';
    protected $_messages = [];

    /**
     * Stores filename(s) in an array
     * @var string 
     */
    protected $_filenames = [];

    /**
     * Accepts one parameter - the path to an image.
     * The constructor begins with a conditional stmt that checks that $image
     * is a file and is readable. If so, it's passed to getimagesize() where the
     * result is stored in $details. Otherwise, $details is set to null and an
     * error message is added to the _messages property.
     * 
     * Side Note: getimagesize() returns an array containing:
     * Width (In Pixels), Height, An integer indicating the type of image, 
     * A string w/correct width & height attributes (for the <img> tag)
     * MIME type of the image, channels (3 for RGB and 4 for CMYK images)
     * and bits - the number of bits for each color. If the value passed as
     * an argument to getimagesize() isn't an image, it returns false.
     * 
     * Consequently, if $details is an array, you know you're dealing w/an image.
     * 
     * @param $image
     */
    public function __construct($image) {
        if (is_file($image) && is_readable($image)) {
            $details = getimagesize($image);
        } else {
            $details = null;
            $this->_messages[] = "4 Cannot open $image.";
        }
        # if getimagesize() returns an array, it looks like an image
        if (is_array($details)) {
            $this->_original = $image;
            $this->_originalwidth = $details[0];
            $this->_originalheight = $details[1];
            $this->_basename = pathinfo($image, PATHINFO_FILENAME);
            # check the MIME type
            $this->checkType($details['mime']);
        } else {
            $this->_messages[] = "4 $image doesn't appear to be an image.";
        }
    }

    /**
     * Checks that $destination is a directory and is writeable. If not, an 
     * error message is added to the _messages property.
     * Before assigning the value of $destination to the $destination property, 
     * the code checks whether the value submitted ends in a forward or back slash.
     * It does so by extracting the final character in $destination using substr().
     * 
     * It's good practice to check for both forward & backslashes alike as
     * this ensures Windows users that type backslashes out of habit do not
     * run into an issue. Two backslashes are required as PHP uses them to
     * escape quotes.
     * 
     * If the conditional confirms that the final character is a forward or
     * backslash, $destination is assigned to the $destination property.
     * Otherwise, the else block concatenates the constant DIRECTORY_SEPARATOR 
     * to the end of the $destination before assigning it to the $destination property.
     * 
     * The DIRECTORY_SEPARATOR constant auto chooses the proper slash type
     * depending on the OS the script is running on.
     *
     * @param $destination
     */
    public function setDestination($destination) {
        if (is_dir($destination) && is_writable($destination)) {
            # get last character
            $last = substr($destination, -1);
            # add a trailing slash if missing
            if ($last == '/' || $last == '\\') {
                $this->_destination = $destination;
            } else {
                $this->_destination = $destination . DIRECTORY_SEPARATOR;
            }
        } else {
            $this->_messages[] = "4 Cannot write to $destination.";
        }
    }

    /**
     * This uses preg_match(), which takes a regex as its first parameter and
     * searches for a match in the value passed as the 2nd parameter. RegEx's
     * need to be wrapped in a pair of matching delimiter chars, typically
     * forward slashes. 
     * ^ tells the regex to start at the beginning of the string. 
     * \w is a regex token that matches any alphanumeric character or an underscore.
     * + means match the preceding token or character one or more times
     * $ means match the end of the string.
     * In plain english, the regex matches a string that contains only
     * alphanumeric characters and underscores. If the string contains spaces
     * or special characters, it will not match.
     * 
     * Then strpos() finds the position of the first underscore. If the first
     * character is an underscore, the value returned by strpos() is 0. However,
     * if $suffix doesn't contain an underscore, then strpos() returns false.
     * This is why the !== 0 not identical operator is used.
     * 
     * So if the suffix doesn't begin w/an underscore, one is added. Else, 
     * the original value is preserved.
     * 
     * @param $suffix
     */
    public function setSuffix($suffix) {
        if (preg_match('/^\w+$/', $suffix)) {
            if (strpos($suffix, '_') !== 0) {
                $this->_suffix = '_' . $suffix;
            } else {
                $this->_suffix = $suffix;
            }
        } else {
            $this->_suffix = '';
        }
    }

    /**
     * This checks for $_canProcess = true and that the width of the original image
     * is not 0. The second test is required due to getimagesize() setting the width 
     * and height to 0 if it can't determine the size. If the _originalwidth property
     * is 0, an error is added to the $_messages array. 
     */
    public function create() {
        if ($this->_canProcess && $this->_originalwidth != 0) {
            $this->calculateSize($this->_originalwidth, $this->_originalheight);
            $this->createThumbnail();
        } elseif ($this->_originalwidth == 0) {
            $this->_messages[] = '4 Cannot determine size of ' . $this->_original;
        }
    }

    /**
     * Simply returns any set _messages
     * 
     * @return array
     */
    public function getMessages() {
        return $this->_messages;
    }
    
    public function getFilenames() {
        return $this->_filenames;
    }

    protected function checkType($mime) {
        $mimetypes = array('image/jpeg', 'image/png', 'image/gif');
        if (in_array($mime, $mimetypes)) {
            $this->_canProcess = true;
            # extract the characters after 'image/'
            $this->_imageType = substr($mime, 6);
        }
    }

    /**
     * Checks w/a conditional statement if the width and height of the original
     * image are less than or equal to the _maxSize. If so, no resizing is necessary
     * and the scaling ratio is set to 1. The elseif block checks if teh width is 
     * greater than than the height. If so, the width is used to calculate the
     * scaling ratio. The else block is invoked if the height is greater or
     * both sides are equal. In either case, the height is used to calculate the ratio.
     * 
     * The last two lines multiply the original width & height by the scaling ratio 
     * and assign the results to teh $_thumbwidth and $_thumbheight properties.
     * 
     * The calculation is wrapped in the round() function which rounds the result
     * to the nearest whole number.
     * 
     * @param $width
     * @param $height
     */
    protected function calculateSize($width, $height) {
        if ($width <= $this->_maxSize && $height <= $this->_maxSize) {
            $ratio = 1;
        } elseif ($width > $height) {
            $ratio = $this->_maxSize / $width;
        } else {
            $ratio = $this->_maxSize / $height;
        }
        $this->_thumbwidth = round($width * $ratio);
        $this->_thumbheight = round($height * $ratio);
    }

    /**
     * The image resource for the orignal image needs to be specific to it's MIME
     * type. The checkType() method stores the MIME type as jpeg, png or gif. So
     * the switch checks the mime type & matches it to the appropriate function
     * and passes the original image as a parameter.
     * 
     * @return $r image resource
     */
    protected function createImageResource() {
        switch ($this->_imageType) {
            case 'jpeg':
                $r = imagecreatefromjpeg($this->_original);
                break;
            case 'png':
                $r = imagecreatefrompng($this->_original);
                break;
            case 'gif':
                $r = imagecreatefromgif($this->_original);
                break;
        }
        return $r;
    }

    function imagecreatefromjpegexif($filename) {
        $img = imagecreatefromjpeg($filename);
        $exif = exif_read_data($filename);
        if ($img && $exif && isset($exif['Orientation'])) {
            $ort = $exif['Orientation'];

            if ($ort == 6 || $ort == 5) {
                $img = imagerotate($img, 270, null);
            }
            if ($ort == 3 || $ort == 4) {
                $img = imagerotate($img, 180, null);
            }
            if ($ort == 8 || $ort == 7) {
                $img = imagerotate($img, 90, null);
            }
            if ($ort == 5 || $ort == 4 || $ort == 7) {
                imageflip($img, IMG_FLIP_HORIZONTAL);
            }
        }
        imagejpeg($img, $filename);
    }

    /**
     * Creates an image resource
     * 
     * Passes the thumbs width and height to 
     * imagecreatetruecolor() and we now have 2 image resources that are passed 
     * onward and upward to imagecopyresampled(). I don't have time at the moment
     * to go into detail on how this function operates though I can state that this 
     * is where the work on the generated thumbnail is done. It's resized, named and saved.
     * 
     */
    protected function createThumbnail() {
        $resource = $this->createImageResource();
        $thumb = imagecreatetruecolor($this->_thumbwidth, $this->_thumbheight);
        imagecopyresampled($thumb, $resource, 0, 0, 0, 0, $this->_thumbwidth, $this->_thumbheight, $this->_originalwidth, $this->_originalheight);
        $newname = $this->_basename . $this->_suffix;

        switch ($this->_imageType) {
            case 'jpeg':
                $newname .= '.jpg';
                $success = imagejpeg($thumb, $this->_destination . $newname);
                #imagecreatejpegexif($this->_destination . $newname);
                break;
            case 'png':
                $newname .= '.png';
                $success = imagepng($thumb, $this->_destination . $newname);
                break;
            case 'gif':
                $newname .= '.gif';
                $success = imagegif($thumb, $this->_destination . $newname);
        }
        switch ($success) {
            case false:
                $this->_messages[] = "4 Couldn't create a thumbnail for " .
                        basename($this->_original);
                break;
            default:
                $this->_messages[] = "1 $newname created from " . basename($this->_original) . "";
                $this->_filenames[] = $newname;
        }
        imagedestroy($resource);
        imagedestroy($thumb);
    }

}
