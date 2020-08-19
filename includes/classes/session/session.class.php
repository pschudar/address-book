<?php

namespace session;

class Session {

    private $admin_id;
    public $username;
    private $last_login;
    public $user_hash;
    private $full_name;
    private $role_name;
    private $role_permissions;

    /**
     * Constant that holds the max login age
     * 
     * Stored in seconds, the session will expire after this time has passed.
     * 
     * @var int
     */
    public const MAX_LOGIN_AGE = 60 * 60 * 24; # 1 day / 24 hrs / 86,400 seconds

    /**
     * Initializes a session & sets session variables
     */
    public function __construct() {
        session_start();
        $this->checkStoredLogin();
    }

    /**
     * Setter & Getter for session messages
     * 
     * @param string $msg
     * @param bool $err
     * @return string
     */
    public function message(string $msg = '', bool $err = false) {
        switch (!empty($msg)) {
            case true:
                return self::setSessionMessage($msg, $err);
            default:
                return self::getSessionMessage($msg);
        }
    }

    public function displaySessionMessage() {
        $msg = $this->message();
        if (isset($msg) && $msg != '') {
            switch ($_SESSION['error']) {
                case true:
                    $class = 'w3-pale-red';
                    $icon = 'fa fa-exclamation-triangle';
                    break;
                default:
                    $class = 'w3-pale-green';
                    $icon = 'fa fa-check-circle';
            }

            $this->clearMessage();
            $alert = "<div id='alertBox' class='w3-container w3-display-container w3-round $class w3-border w3-theme-border w3-margin-bottom'>";
            $alert .= "<span onclick=\"this.parentElement.style.display='none'\" aria-label='close' id='closeAlert' class='w3-button w3-theme-l3 w3-display-topright'>";
            $alert .= "<span aria-hidden='true'>&times</span></span>";
            $alert .= "<p><span class='{$icon}'></span> &nbsp;";
            $alert .= "<span class='sessMsg'></span>" . \utility\Utility::h($msg) . '</span></p></div>';
            return $alert;
            #return "<div id='message' class=\"alert alert-{$class} alert-dismissible fade show\" role='alert'><span class='{$icon}'></span> <span class='sessMsg'>" . \utility\Utility::h($msg) . '</span><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
        }
    }

    /**
     * Admins are required to authenticate. If unauthenticated, forward to login.
     */
    public function requireLogin() {
        if (!$this->isLoggedIn()) :
            \utility\Utility::redirectTo(\utility\Utility::urlFor('/staff/login.php'));
        endif;
    }

    public function displayErrors($errors = []) {
        $class = 'w3-pale-red';
        $icon = 'fa fa-exclamation-triangle';
        $iconColor = 'w3-text-danger';
        $getDo = filter_input(INPUT_GET, 'do', 513);
        if(isset($getDo) && $getDo == 'logout') {
            $class = 'w3-theme-l5';
            $icon = 'fa fa-info-circle';
            $iconColor = 'w3-text-info';
        }
        $output = '';
        if (!empty($errors)) {
            $output .= '<ul class="w3-ul">';
            foreach ($errors as $error) {
                $output .= "<li class='{$class}'><span class='{$icon} {$iconColor}'></span> &nbsp;" . \utility\Utility::h($error) . '</li>';
            }
            $output .= "</ul>";
        }
        return $output;
    }

    /**
     * Logs the user in
     * 
     * @param object \user\Admin $admin
     * @return boolean
     */
    public function login(\user\Admin $admin) {
        if ($admin) {
            # prevent session fixation attacks
            session_regenerate_id();
            $this->full_name = $_SESSION['full_name'] = $admin->fullName();
            $this->admin_id = $_SESSION['admin_id'] = $admin->id;
            $this->username = $_SESSION['username'] = $admin->username;
            $this->last_login = $_SESSION['last_login'] = time();
            $this->user_hash = $_SESSION['user_hash'] = md5(microtime());
            $this->role_name = $_SESSION['role_name'] = $admin->role_name;
            $this->role_permissions = $_SESSION['role_permissions'] = $admin->role_permissions;
            return true;
        }
        return false;
    }

    /**
     * Checks if admin_id is set and if recently logged in - if so, returns true.
     * 
     * Otherwise, the method returns false.
     * @return boolean
     */
    public function isLoggedIn() {
        return isset($this->admin_id) && $this->lastLoginIsRecent();
    }

    /**
     * Unsets active session variables as well as class properties.
     * 
     * Sets an expired cookie and destroys the session, 
     * effectively logging a user out of the system.
     * @return void
     */
    public function logout() {
        self::unsetSessions();
        self::expireCookie();
        self::destroySession();
        unset($this->admin_id);
        unset($this->username);
        unset($this->last_login);
        unset($this->user_hash);
        unset($this->full_name);
        unset($this->role_name);
        unset($this->role_permissions);
    }

    /**
     * A simple method to check if the user is authorized
     * 
     * Example Usage: 
     * <code>
     * if(!hasPermission) { // show unauth msg } else { // show content }
     * </code>
     * 
     * @param string $granted
     * @param int $required
     * @return bool
     */
    public function hasPermission($granted, $required) {
        $permissionsArray = explode(',', $granted);
        return in_array($required, $permissionsArray);
    }

    /**
     * Clears the session message
     */
    private function clearMessage() {
        unset($_SESSION['message']);
        unset($_SESSION['error']);
    }

    private function checkStoredLogin() {
        if (isset($_SESSION['admin_id'])) {
            $this->admin_id = $_SESSION['admin_id'];
            $this->username = $_SESSION['username'];
            $this->last_login = $_SESSION['last_login'];
            $this->full_name = $_SESSION['full_name'];
            $this->role_name = $_SESSION['role_name'];
            $this->role_permissions = $_SESSION['role_permissions'];
        }
    }

    /**
     * Sets the session message to string $msg
     * 
     * @param string $msg
     * @return string
     */
    private static function setSessionMessage(string $msg, bool $error) {
        $_SESSION['error'] = $error;
        return $_SESSION['message'] = $msg;
    }

    /**
     * Retrieves the stored session message string
     * 
     * @return string
     */
    private static function getSessionMessage() {
        return $_SESSION['message'] ?? '';
    }

    private function lastLoginIsRecent() {
        if (!isset($this->last_login)) {
            return false;
        } elseif (($this->last_login + self::MAX_LOGIN_AGE) < time()) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * An array of active session variables is initialized as $sessionArray and
     * is looped. For each active variable, this method unsets it.
     * 
     * @return void
     * @access private
     */
    private static function unsetSessions() {
        $sessionArray = [
            'admin_id',
            'username',
            'last_login',
            'user_hash',
            'full_name',
            'role_name'
        ];

        foreach ($sessionArray as $active) {
            if (isset($_SESSION[$active])) :
                unset($_SESSION[$active]);
            endif;
        } unset($sessionArray);
    }

    /**
     * Checks the ini directive for session.use_cookies. 
     * 
     * if true, an expired cookie is set.
     * @return void
     * @access private
     */
    private static function expireCookie() {
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']
            );
        }
    }

    /**
     * Sets $_SESSION as an empty array and destroys it.
     * 
     * @return void
     * @access private
     */
    private static function destroySession() {
        $_SESSION = [];
        session_destroy();
    }

}
