<?php

namespace utility;

/**
 * Utility
 * 
 * Provides a container in which to hold validation and misc methods
 * 
 * @category utility
 * @package address book
 * @author Paul Schudar
 * @copyright Copyright (c) 2020, Paul Schudar
 * @license https://opensource.org/licenses/mit-license.php MIT License
 */
class Utility {

    use \utility\Validation;

    /**
     * Runs a string through htmlspecialchars()
     * 
     * Provides easy access to an otherwise lengthy function name.
     * 
     * @param string $string
     * @return string
     */
    public static function h($string = '') {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Determines if the request was a post request
     * 
     * @return bool
     */
    public static function isPostRequest() {
        return strtolower(SERVER_REQUEST_METHOD) == 'post';
    }

    /**
     * Determines if the request was an ajax request
     * 
     * @return Boolean
     */
    public static function isAjaxRequest() {
        return !empty(HTTP_XRW) &&
                strtolower(HTTP_XRW) == 'xmlhttprequest';
    }

    /**
     * Encodes a string for use within a URL.
     * 
     * Quicker than using urlencode() repeatedly.
     * 
     * @param type $string
     * @return type
     */
    public static function u(string $string = '') {
        return urlencode($string);
    }

    /**
     * Combines methods h and u in one for ease of use.
     * 
     * Runs $string through htmlspecialchars() then through urlencode().
     * Returns the filtered/encoded $string for use in a url.
     * 
     * @param string $string
     * @return string
     */
    public static function hu(string $string = '') {
        $h = self::h($string);
        $u = self::u($h);
        return $u;
    }

    /**
     * Quicker than typing rawurlencode();
     * 
     * @param string $string
     * @return type
     */
    public static function ru($string = '') {
        return rawurlencode($string);
    }

    /**
     * Redirects a user elsewhere
     * 
     * Useful if the user is not authorized to be there.
     */
    public static function redirectTo($location) {
        header('Location: ' . $location);
        exit;
    }

    /**
     * Redirects a user to an access denied page
     * 
     * Wraps redirectTo & urlFor for ease of use
     */
    public static function accessDenied() {
        self::redirectTo(self::urlFor('includes/shared/access_denied.php'));
    }

    /**
     * Simplifies adding file paths.
     * 
     * @param string $script_path
     * @return string
     */
    public static function urlFor($script_path) {
        # add the leading '/' if not present
        if ($script_path[0] != '/') {
            $script_path = "/" . $script_path;
        }
        return WWW_ROOT . $script_path;
    }

    /**
     * Determines the browsers ability to accept compression
     * 
     * Used in initialize.php
     */
    public static function outputBuffering() {
        switch (substr_count(HTTP_ENCODING, 'gzip')) :
            case true:
                return ob_start('ob_gzhandler');
            default:
                return ob_start();
        endswitch;
    }

    /**
     * Takes MySQL timestamp and formats it for display
     * $altFormat optionally allows one to modify the returned timestamp
     * 
     * <code>
     * <?php echo 'Posted on ' . CallingClass::formatDate($timestamp_var, 'l, F jS Y \a\t g:ia'); ?>
     * </code>
     * Resolves to : Posted on Sunday, August 9th 2015 at 7:34am
     * @param $timestamp datetime stored within database
     * @param $altFormat optional - allows for the modification of the returned timestamp
     * @param $format optional - allows for the modification of the stored timestamp
     * @return string A string representing the stored datetime
     */
    public static function formatDate($timestamp, $altFormat = '', $format = 'Y-m-d H:i:s') {
        if(\utility\Utility::isBlank($timestamp)) {
            return;
        } else if ($timestamp == '0000-00-00') {
            return;
        }
        $date = \DateTime::createFromFormat($format, $timestamp);
        switch (!empty($altFormat)) {
            case true:
                return \utility\Utility::h($date->format($altFormat));
            default:
                return \utility\Utility::h($date->format('m-d-Y h:i a'));
        }
    }

}
