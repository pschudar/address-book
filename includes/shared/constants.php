<?php

/**
 * Holds the current version number
 * 
 * @var string
 */
const VERSION_NO = 'v1.1.0.2';

/**
 * Filters Super-global _SERVER['REQUEST_METHOD'] with FILTER_SANITIZE_FULL_SPECIAL_CHARS (id: 522)
 * 
 * @var string
 */
$server_request_method = filter_input(INPUT_SERVER, 'REQUEST_METHOD', 522);

/**
 * Constant that uses $server_request_method's value
 * 
 * Possible values include: GET, POST, or REQUEST
 * @var string
 */
define('SERVER_REQUEST_METHOD', $server_request_method);

/**
 * Filters Super-global _SERVER['HTTP_X_REQUESTED_WITH'] with FILTER_SANITIZE_STRING (id: 513)
 * 
 * @var string
 */
$server_http_xrw = filter_input(INPUT_SERVER, 'HTTP_X_REQUESTED_WITH', 513);

/**
 * Constant that uses $server_http_xrw's value
 * 
 * Possible values include XMLHttpRequest
 */
define('HTTP_XRW', $server_http_xrw);

/**
 * Filters Super-global _SERVER['PHP_SELF'] with _FULL_SPECIAL_CHARS (id: 522)
 */
$php_self = filter_input(INPUT_SERVER, 'PHP_SELF', 522);

/**
 * Constant that utilizes $php_self's value for the main form action attribute
 * 
 * @var string
 */
define('PHP_SELF', $php_self);

/**
 * Constant used as a return value in many different functions. 
 * 
 * This simply provides an easy way to offer up a uniform message across the board.
 * In the unlikely event that a unit that does not exist in in the from_unit or to_unit
 * drop down select lists, this is the message received by the end-user.
 * 
 * @var string
 */
const UNSUPPORTED = 'Unsupported Unit';

/**
 * Constant used as a return value. Currently unsure if this will remain.
 * 
 * @var string
 */
const INVALID = 'Enter a valid value';
