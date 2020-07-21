<?php

/**
 * Holds the current version number
 * 
 * @var string
 */
const VERSION_NO = 'v1.0.0.0';

/**
 * Filtering _SERVER Superglobals
 * 
 * [*] ID 522: FILTER_SANITIZE_FULL_SPECIAL_CHARS
 * [*] ID 513:  FILTER_SANITIZE_STRING or _STRIPPED
 */
$http_encoding = filter_input(INPUT_SERVER, 'HTTP_ACCEPT_ENCODING', 522);
$server_script_name = filter_input(INPUT_SERVER, 'SCRIPT_NAME', 522);
$server_protocol = filter_input(INPUT_SERVER, 'SERVER_PROTOCOL', 522);
$server_request_method = filter_input(INPUT_SERVER, 'REQUEST_METHOD', 522);
$server_http_xrw = filter_input(INPUT_SERVER, 'HTTP_X_REQUESTED_WITH', 513);
$php_self = filter_input(INPUT_SERVER, 'PHP_SELF', 522);

/**
 * Assign values to commonly used constants
 * 
 * __FILE__ will return the path to -this- file
 * dirname() returns the path to the parent directory
 * 
 * These constants make use of the filtered Superglobals
 */
define('INCLUDE_PATH', dirname(__FILE__));
define('DS', DIRECTORY_SEPARATOR);
define('SHARED_PATH', INCLUDE_PATH . DS . 'shared' . DS);
define('PROJECT_PATH', dirname(INCLUDE_PATH));
define('SERVER_PROTOCOL', $server_protocol); # HTTP/1.1
define('SERVER_REQUEST_METHOD', $server_request_method); # GET | POST | REQUEST
define('HTTP_XRW', $server_http_xrw); # XmlHttpRequest
define('HTTP_ENCODING', $http_encoding);
define('PHP_SELF', $php_self);
define('PROFILE_IMAGE_PATH', PROJECT_PATH . DS . 'assets' . DS . 'images' . DS . 'profile' . DS); # must end with a trailing slash
define('MODAL_PATH', PROJECT_PATH . DS . 'staff' . DS . 'contacts' . DS .  'modals' . DS);

/**
 * $public_end assigns the root URL to a pre-filtered constant
 * 
 * [*] No need to include the domain
 * [*] Use same document root as web server
 * [*] Can hard code a value:
 * [*] define("WWW_ROOT", '/~username/project_directory/contacts');
 * [*] define("WWW_ROOT", '');
 * [*] Can dynamically find everything in the URL up to "/address_book"
 */
$public_end = strpos($server_script_name, DS . 'address_book') + 13;
$doc_root = substr($server_script_name, 0, $public_end);
define('WWW_ROOT', $doc_root);

/**
 * Address Book automatic class loader 
 * 
 * Auto-magically loads classes without having to specifically require them.
 * Will work on Windows, Linux, Mac, etc.
 * Requires a naming structure such as classname.class.php. 
 * 
 * Works well with namespaces. namespace test requires the class file to 
 * be placed in a subdirectory called 'test'.
 * 
 * @param class $class
 */
function ab_autoload($class) {
    $filename = INCLUDE_PATH . DS . 'classes' . DS . str_replace('\\', DS, strtolower($class)) . '.class.php';
    if (file_exists($filename) && is_readable($filename)) :
        require_once($filename);
    endif;
}

/**
 * Register the autoload function
 */
spl_autoload_register('ab_autoload');

/**
 * enable output buffering
 */
\utility\Utility::outputBuffering();

/**
 * Include database credentials
 */
require_once(INCLUDE_PATH . DS . 'db_credentials.php');

/**
 * Include database functions
 */
require_once(INCLUDE_PATH . DS . 'db_functions.php');

/**
 * Connect to the database
 */
$database = databaseConnect();

/**
 * Set the database
 */
\database\DatabaseObject::setDatabase($database);

/**
 * Start a new session
 */
$session = new \session\Session;
