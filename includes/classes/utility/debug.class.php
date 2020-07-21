<?php

namespace utility;

/**
 * Debug
 * 
 * Provides a container in which to hold debugging methods
 * 
 * @category debug
 * @package address book
 * @author Paul Schudar
 * @copyright Copyright (c) 2020, Paul Schudar
 * @license https://opensource.org/licenses/mit-license.php MIT License
 */
trait Debug {

    /**
     * A utility method designed to output results to the browser's dev console.
     * 
     * This is quite useful for debugging SQL queries.
     * 
     * @param string $output
     * @param boolean $with_script_tags
     */
    public static function console_log($output, $with_script_tags = true) {
        $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) .
                ');';
        if ($with_script_tags) {
            $js_code = '<script>' . $js_code . '</script>';
        }
        return $js_code;
    }

    /**
     * A wrapper for console_log()
     * 
     * Allows to easily add debugging capability to most any method.
     * @param string $sql
     */
    protected static function debug($sql) {
        if (static::$debug) {
            echo self::console_log($sql);
        } return;
    }

}
