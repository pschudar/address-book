<?php

namespace utility;

/**
 * Utility Validation
 * 
 * Provides a container in which to hold validation methods
 * 
 * @category validation
 * @package address book
 * @author Paul Schudar
 * @copyright Copyright (c) 2020, Paul Schudar
 * @license https://opensource.org/licenses/mit-license.php MIT License
 */
trait Validation {

    /**
     * Validate data presence, or the lack thereof, actually.
     * 
     * Trims white space so spaces that are input will not count as data
     * Uses the identical comparison operator to mitigate false positives
     * empty() considers '0' to be empty, this ensures data is truly present
     * 
     * Used heavily within admin.class.php
     * 
     * @param type $value
     * @return type
     */
    public static function isBlank($value) {
        switch (is_array($value)) {
            case true:
                return !isset($value) || array_map('trim', $value) === [];
            default:
                return !isset($value) || trim($value) === '';
        }
    }

    /**
     * Validate data presence
     * 
     * If a string is not blank (!is_blank), returns true. False, otherwise.
     * 
     * [*] Validates the presence of data
     * [*] Reverse of is_blank()
     * [*] usage ex: if(has_presence($variable)) { ... }
     * [*] Using validation names with a "has_" prefix makes it read like a sentence.
     * @param string $val
     * @return boolean
     */
    public static function hasPresence($val) {
        return !self::isBlank($val);
    }

    /**
     * Tests for a length greater than an int in a supplied string.
     * 
     * [*] Validate string length
     * [*] Allows spaces to count towards length
     * [*] Can be modified to use trim() if spaces should not count
     * [*] Usage ex: 
     *     <code>if(hasLengthGreaterThan('test', 3);</code>
     * [*] cont: returns true as the string 'test' has 4 letters and we're testing for > 3
     * [*] The practicality and use cases become clear after viewing the hasLength() method
     * @param string $value
     * @param int $min
     * @return boolean
     */
    public static function hasLengthGreaterThan($value, $min) {
        $length = strlen($value);
        return $length > $min;
    }

    /**
     * Tests for a length less than an int in a supplied string
     * 
     * [*] Validate string length
     * [*] Allows spaces to count towards length
     * [*] Can be modified to use trim() if spaces should not count
     * [*] Usage ex: 
     * 
     *     <code>if(hasLengthLessThan('abcdefg', 4))<code>
     * 
     * [*] cont: returns false as the string 'abcdefg' has 7 characters which is greater than 4
     * [*] The practicality and use cases become clear after viewing the hasLength() method
     * 
     * @param string $value
     * @param int $max
     * @return boolean
     */
    public static function hasLengthLessThan($value, $max) {
        $length = strlen($value);
        return $length < $max;
    }

    /**
     * Tests for a string ($value) having a length of the integer ($exact)
     * 
     * [*] Validate string length
     * [*] Spaces count towards length
     * [*] Can be modified to use trim() if spaces should not count
     * [*] Usage ex: 
     * 
     *    <code>hasLengthExactly('test', 4)</code> 
     * 
     * returns true as the length of the string 'test' is indeed exactly 4 characters in length
     * 
     * @param string $value
     * @param int $exact
     * @return boolean
     */
    public static function hasLengthExactly($value, $exact) {
        $length = strlen($value);
        return $length == $exact;
    }

    /**
     * Tests if a string has a length between two integers
     * 
     * [*] Validate string length
     * [*] Combines functions: _greater_than, _less_than, _exactly
     * [*] Spaces count towards length
     * [*] Can be modified to use trim() if spaces should not count
     * [*] Usage ex: <code>has_length('test', ['min' => 3, 'max' => 5])</code>
     * [*] cont: would return true as 'test' has 4 characters which is between 3 & 5
     * @param string $value
     * @param array $options
     * @return boolean
     */
    public static function hasLength($value, $options) {
        if (isset($options['min']) && !self::hasLengthGreaterThan($value, $options['min'] - 1)) {
            return false;
        } elseif (isset($options['max']) && !self::hasLengthLessThan($value, $options['max'] + 1)) {
            return false;
        } elseif (isset($options['exact']) && !self::hasLengthExactly($value, $options['exact'])) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Tests that a particular value is in an array
     * 
     * [*] Validate inclusion in a set / array
     * [*] Usage ex: 
     * 
     *     <code>hasInclusionOf(6, [2,4,6,8]);</code>
     * 
     * returns true due to the value, 6, being found in the set (the array).
     * 
     * @param string or int $value
     * @param array $set
     * @return boolean
     */
    public static function hasInclusionOf($value, $set) {
        return in_array($value, $set);
    }

    /**
     * Tests that a particular value is not in a set / array
     * 
     * [*] Validate exclusion from a set / array
     * [*] Usage ex: 
     * 
     *     <code>hasExclusionOf(6, [2,4,6,8]);</code>
     * 
     * returns false due to the value, 6, being found in the set (the array)
     * 
     * @param string or int $value
     * @param array $set
     * @return boolean
     */
    public static function hasExclusionOf($value, $set) {
        return !in_array($value, $set);
    }

    /**
     * Validates the inclusion of characters
     * 
     * [*] strpos returns string start position or boolean false
     * [*] uses !== operator to prevent position 0 from being considered false
     * [*] strpos is faster than preg_match()
     * [*] Usage ex: 
     * 
     *     <code>hasString('nobody@thisSite.org', '.org');</code>
     * 
     * returns false as '.org' is found within 'nobody@thisSite.org'
     * 
     * @param string $value
     * @param string $required_string
     * @return boolean
     */
    public static function hasString($value, $required_string) {
        return strpos($value, $required_string) !== false;
    }

    /**
     *  Validates proper format for an email address
     * 
     * [*] format: [chars]@[chars].[2+ letters]
     * [*] Uses preg_match - returns 1 for a match, 0 for no match
     * 
     * Official Documentation:
     * http://php.net/manual/en/function.preg-match.php
     * 
     * @param string $value
     * @return boolean
     */
    public static function hasValidEmailFormat($value) {
        $email_regex = '/\A[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}\Z/i';
        return preg_match($email_regex, $value) === 1;
    }

    /**
     * Tests whether a user is signing up with a unique user name - Not currently public
     * 
     * In Personal Blog, this function is only used when one admin is adding another admin.
     * Therefore, it does not pose a security risk by exposing usernames to the public.
     * 
     * [*] Validates whether a supplied username is unique
     * [*] For new records, provide only the username
     * [*] For existing records, provide the current ID as the second argument
     * [*] Usage ex: 
     * 
     *     <code><?php Utility::hasUniqueUsername('johnhancock', 1); ?></code>
     * 
     * @param string $username
     * @param int $current_id
     * @return boolean
     */
    public static function hasUniqueUsername($username, $current_id = '0') {
        $admin = \user\Admin::findByUsername($username);

        if ($admin === false || $admin->id == $current_id) {
            # if it is unique
            return true;
        } else {
            # if it is not unique
            return false;
        }
    }

}
