<?php

namespace file;

/**
 * Upload
 * 
 * @package Upload
 * @category File
 * @internal version 0.7 Created 01.11.19 | Last Modified 01.11.19
 * 
 * The Upload class emphasizes security for uploading files
 * - limits max size of individual files
 * - size is configurable, however, it prevents clients from exceeding limits set in PHP's INI file.
 * - checks MIME type or alternatively, it renames suspect files listed in the $_notTrusted property.
 * The class is flexible
 * - automatically handles single or multiple file uploads
 * - most default settings can be edited without changing the class definition
 * Default Settings
 * - Maximum file size: 2MB (PHP's default setting)
 * - Restricted to image files
 * - Appends .upload to potentially harmful filename extensions to mitigate risk
 * - Files with duplicate filenames are automatically renamed by inserting a number before the file extension
 * 
 * ALL of these defaults can be overridden using the public methods outlined below within the processing script
 * To ensure compatibility and avoid any conflicts, this class uses a namespace.
 * 
 * Import it as such: use\file; $upload = new Upload($path);
 * 
 * This class contains 5 public methods that allow for configuration changes.
 * 
 * setMaxSize() - Resets the maximum size of individual files
 * - 1 parameter, $bytes. Not surprisingly, it must be expressed as a number of bytes.
 * - optional: If it's not called in the upload processing script, the default 2MB limit is used.
 * - If used, must be called before the upload() method.
 * 
 * getMaxSize() - Reports max size in Kilobytes formatted to one decimal place.
 * 
 * allowAllTypes() - Removes the restriction on the MIME types that can be uploaded
 * - 1 optional parameter, $_suffix (defaults to null).
 * - Usage: Pass a string with a preceding dot (.) for the suffix.
 * - Ex: $upload->allowAllTypes('.webUpload');
 * - someExecutable.exe will be uploaded as someExecutable.exe.webUpload - rendering it harmless
 * - Filenames remain unchanged if $_suffix is set to an empty string.
 * - optional: If it's not called in the upload processing script, uploads are restricted to specific mime types
 * 
 * upload() - Saves the file(s) to the destination directory. Spaces in file names are replaced by underscores.
 * - 1 optional parameter, $_renameDuplicates (defaults to boolean true)
 * - Without an argument being passed, ie, $upload->upload(); duplicate files are renamed by putting a number before the file extension.
 * - To allow for file overwriting instead, pass 0 or boolean false as an argument to this method.
 * - required: Without calling the upload method, nothing is uploaded.
 * -- MUST be called after setMaxSize and allowAllTypes if they are being used.
 * 
 * getMessages() - Returns an array of messages reporting the status of uploads.
 * - 0 parameters.
 * - MUST be called after upload()
 * 
 * This class contains 2 public static methods
 * 
 * convertToBytes($val) - Useful for converting the value of post_max_size and upload_max_filesize from php.ini into bytes.
 * The value can then be used to enforce the server limits.
 * - optional
 * 
 * convertFromBytes($bytes) - Accepts a value, in bytes, and converts it to a human-friendly value.
 * - Ex: $converted = Upload::convertFromBytes($bytes);
 * 
 * 
 * NOTE: Please ensure that the $_destination property points to a writeable directory.
 */
class Upload {

    /**
     * The location to store uploaded files. The value is
     * set when an instance of Upload is created.
     * 
     * @var string
     */
    protected $_destination;

    /**
     * Maintains a stack of class messages
     * 
     * @var array
     */
    protected $_messages = [];

    /**
     * Max file size allowed to be uploaded, in bytes.
     * 1MB = 1,048,576 Bytes (binary) / 2MB = 2,097,152 Bytes (binary)
     * Must be an integer here. It cannot be a calculation.
     * 
     * @var int
     */
    protected $_maxSize = 2097152;

    /**
     * Contains an array of image MIME types accepted as an upload
     * Currently, it limits the allowed file types to .gif, .jpg, .png and .webp images
     *
     * @var array
     */
    protected $_permitted = [
        'image/jpeg',
        'image/pjpeg',
        'image/gif',
        'image/png',
        'image/webp'
    ];

    /**
     * Contains a renamed file name
     * 
     * @var string
     */
    protected $_newName;

    /**
     * Controls whether the MIME type will be checked against $_permitted.
     * If other file types should be allowed, call the allowAllTypes() method
     * before the upload() method. $_typeCheckingOn will then be false and any
     * type of file will be accepted for upload.
     * 
     * @var boolean
     */
    protected $_typeCheckingOn = true;

    /**
     * Contains an array of un-trusted file extensions. If these types of files
     * are uploaded, they could present a security risk. To mitigate
     * such risks, append an optional suffix to the file to render it harmless.
     * File extensions defined here do not contain a preceding dot (.)
     * 
     * @var array
     */
    protected $_notTrusted = ['bin', 'cgi', 'exe', 'js', 'pl', 'php', 'py', 'sh'];

    /**
     * Optional suffix to append to $_notTrusted file types
     * 
     * @var string
     */
    protected $_suffix = '.upload';

    /**
     * Renaming duplicate files needs to be optional
     * 
     * @var bool
     */
    protected $_renameDuplicates;

    /**
     * Stores filename(s) in an array
     * @var string 
     */
    protected $_filenames = [];

    /**
     * Takes a directory path as an argument which points to the directory
     * that stores uploaded files. This path is then assigned to the property
     * $_destination.
     * 
     * @param string $path
     */
    public function __construct($path) {
        if (!is_dir($path) || !is_writable($path)) {
            $this->_messages[] = '4' . $path . ' must be a valid, writable directory';
        }
        if ($path[strlen($path) - 1] != '/') {
            $path .= '/';
        }
        $this->_destination = $path;
    }

    /**
     * Allows changing the max permitted file size
     * 
     * Checks the submitted value to ensure it is numeric then
     * assigns it to the _maxSize property as an integer.
     * @param int $bytes
     */
    public function setMaxSize($bytes) {
        $serverMax = self::convertToBytes(ini_get('upload_max_filesize'));
        if ($bytes > $serverMax) {
            $this->_messages[] = '4 Max size cannot exceed server limit for individual files: ' . self::convertFromBytes($serverMax);
        }
        if (is_numeric($bytes) && $bytes > 0) {
            $this->_maxSize = (int) $bytes;
        }
    }

    /**
     * Useful for converting the value of post_max_size and upload_max_filesize from php.ini into bytes
     * The value can then be used to enforce the server limits.
     * @param type $value
     * @return int
     */
    public static function convertToBytes($value) {
        $val = trim($value);
        $last = strtolower($val[strlen($val) - 1]);
        if (in_array($last, ['g', 'm', 'k'])) {
            $val = (float) $val;
            switch ($last) {
                case 'g':
                    $val *= 1024;
                case 'm':
                    $val *= 1024;
                case 'k':
                    $val *= 1024;
            }
        }
        return $val;
    }

    /**
     * Accepts a value, in bytes, and converts it to a human-friendly value.
     * 
     * @param int $bytes
     * @return string
     */
    public static function convertFromBytes($bytes) {
        $bytes /= 1024;
        if ($bytes > 1024) {
            return number_format($bytes / 1024, 1) . ' MB';
        } else {
            return number_format($bytes, 1) . ' KB';
        }
    }

    /**
     * Decides whether the MIME type should be checked.
     * 
     * $this->_typeCheckingOn = false; will allow any file type
     * to be uploaded as no MIME types are inspected.
     * Ideally, only trusted users will be uploading files. So we're adding
     * $_suffix as an argument here and define it as true to make it optional. If
     * it's defined as false, the value of $_suffix is defined as an empty string
     * and no suffix is appended to the file.
     *
     * @param $_suffix
     */
    public function allowAllTypes($_suffix = null) {
        $this->_typeCheckingOn = false; # true allows only MIME types in the _permitted property array to be uploaded
        if (!is_null($_suffix)) {
            if (strpos($_suffix, '.') === 0 || $_suffix == '') {
                $this->_suffix = $_suffix;
            } else {
                $this->_suffix = ".$_suffix";
            }
        }
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

    /**
     * Uploads the file or files
     * 
     * Defines $uploaded as first element of $_FILES array so
     * regardless of how the form defines the file, the code will run.
     * Renaming duplicate files needs to be optional, therefore, it is added as an
     * argument to the upload method. It's assigned true by default. To prevent
     * duplicate file renaming, pass false as an argument to the upload() method.
     *
     * @param $_renameDuplicates
     */
    public function upload($_renameDuplicates = true) {
        $this->_renameDuplicates = $_renameDuplicates;
        $uploaded = current($_FILES); # holds a reference to the first element in the $_FILES array
        if (is_array($uploaded['name'])) {
            foreach ($uploaded['name'] as $key => $value) :
                $currentFile['name'] = $uploaded['name'][$key];
                $currentFile['type'] = $uploaded['type'][$key];
                $currentFile['tmp_name'] = $uploaded['tmp_name'][$key];
                $currentFile['error'] = $uploaded['error'][$key];
                $currentFile['size'] = $uploaded['size'][$key];
                if ($this->checkFile($currentFile)) :
                    $this->moveFile($currentFile);
                endif;
            endforeach;
        } else {
            if ($this->checkFile($uploaded)) :
                $this->moveFile($uploaded);
            endif;
        }
    }

    public function getMessages() {
        return $this->_messages;
    }

    protected function checkFile($file) {
        if ($file['error'] != 0) {
            $this->getErrorMessage($file);
            return false;
        }
        if (!$this->checkSize($file)) {
            return false;
        }
        if ($this->_typeCheckingOn) {
            if (!$this->checkType($file)) :
                return false;
            endif;
        }
        $this->checkName($file);
        return true;
    }

    /**
     * This is a switch statement that reports error levels on the uploaded file 
     * to add a suitable message to the $_messages array.
     * 
     * - The first integer in _messages determines the styling. 
     * @see class.dashboardwidget.php | dashboardWidget::formatMessages() for more details. 
     * @param array $file
     */
    protected function getErrorMessage($file) {
        switch ($file['error']) {
            case 1:
            case 2:
                $this->_messages[] = '4' . $file['name'] . ' is too big: (max: ' . self::convertFromBytes($this->_maxSize) . ').';
                break;
            case 3:
                $this->_messages[] = '4' . $file['name'] . ' was only partially uploaded.';
                break;
            case 4:
                $this->_messages[] = '4 No file submitted.';
                break;
            case 6: # 5 is undefined
                $this->_messages[] = '4 There is no defined temporary directory.';
                break;
            case 7:
                $this->_messages[] = '4 Cannot write file to disk';
                break;
            case 8:
                $this->_messages[] = '4 Upload stopped by an unspecified PHP extension.';
                break;
            default:
                $this->_messages[] = '4 There was a problem uploading ' . $file['name'];
                break;
        }
    }

    /**
     * Checks the size of a file; If 0 or exceeds _maxSize, an error message is added to the stack.
     *
     * @param array $file
     * @return boolean
     */
    protected function checkSize($file) {
        if ($file['size'] == 0) {
            $this->_messages[] = '4' . $file['name'] . ' is an empty file.';
            return false;
        } elseif ($file['size'] > $this->_maxSize) {
            $this->_messages[] = '4' . $file['name'] . ' exceeds the maximum size for a file ('
                    . self::convertFromBytes($this->_maxSize) . ').';
            return false;
        } else {
            return true;
        }
    }

    /**
     * Checks the type reported by the $_FILES array against the $_permitted property.
     * If the type is included within $_permitted, the method returns true, else false and
     * the reason for rejection is added to the $messages array.
     *
     * @param $file
     * @return boolean
     */
    protected function checkType($file) {
        if (in_array($file['type'], $this->_permitted)) {
            return true;
        } else {
            $this->_messages[] = '4' . $file['name'] . ' is not a permitted type of file.';
            return false;
        }
    }

    /**
     * This method starts setting the _newName property to null. The Upload Class
     * allows for multiple file uploads and this property needs to be reset for each file.
     * str_replace then replaces spaces with underscores and assigns the result to $nospaces.
     * The value of $nospaces is compared with $file['name'] and if they're not the same, 
     * $nospaces is assigned as the value of the $_newName property.
     * 
     * We extract the file extension and assign it to the $extension variable.
     * We then add a suffix to the file only if the $_typeCheckingOn property is false and
     * the $_suffix property is not an empty string. It's also a good idea to add the suffix
     * to files that lack an extension as they're typically executable files on linux servers.
     * 
     * @todo Replace hyphens with underscores. Perhaps create an array of unwanted filename chars?
     *
     * @param array $file
     */
    protected function checkName($file) {
        $this->_newName = null;
        $nospaces = str_replace(' ', '_', $file['name']);
        if ($nospaces != $file['name']) {
            $this->_newName = $nospaces;
        }
        $nameparts = pathinfo($nospaces);
        $extension = isset($nameparts['extension']) ? $nameparts['extension'] : '';
        if (!$this->_typeCheckingOn && !empty($this->_suffix)) {
            if (in_array($extension, $this->_notTrusted) || empty($extension)) {
                $this->_newName = $nospaces . $this->_suffix;
            }
        }
        if ($this->_renameDuplicates) {
            $name = isset($this->_newName) ? $this->_newName : $file['name'];
            $existing = scandir($this->_destination);
            if (in_array($name, $existing)) {
                $i = 1;
                do {
                    $this->_newName = $nameparts['filename'] . '_' . $i++;
                    if (!empty($extension)) :
                        $this->_newName .= ".$extension";
                    endif;
                    if (in_array($extension, $this->_notTrusted)) :
                        $this->_newName .= $this->_suffix;
                    endif;
                } while (in_array($this->_newName, $existing));
            }
        }
    }

    /**
     * Wraps move_uploaded_file internally and returns true if 
     * file upload is successful. Success or Error message is added
     * to the stack of info contained within $_messages array. If the
     * file name was changed by removing spaces or adding a suffix or both the
     * moveFile method uses the amended name when saving the file to $_destination.
     * 
     * It's good practice to alert users of a file name change so
     * the change is output within the $_messages array if needed.
     *
     * @param $file
     */
    protected function moveFile($file) {
        $filename = isset($this->_newName) ? $this->_newName : $file['name'];
        $success = move_uploaded_file($file['tmp_name'], $this->_destination . $filename);
        if ($success) {
            # add the amended filename to the array of uploaded files
            $this->_filenames[] = $filename;
            $result = '1' . $file['name'] . ' was uploaded successfully';
            if (!is_null($this->_newName)) {
                $result .= ', and was renamed ' . $this->_newName;
            }
            $result .= '.';
            $this->_messages[] = $result;
        } else {
            $this->_messages[] = '4 Could not move ' . $file['name'];
        }
    }

}
