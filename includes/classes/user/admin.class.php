<?php

declare(strict_types=1);

namespace user;

class Admin extends \database\DatabaseObject {

    use \shared\Profile;

    static protected $table_name = 'ab_admins';
    static protected $image_xref = 'ab_user_image_xref';
    static protected $db_columns = ['id', 'username', 'name_first', 'name_last', 'email', 'hashed_password', 'role_id', 'status'];
    public $id;
    public $username;
    public $name_first;
    public $name_last;
    public $email;
    public $hashed_password;
    public $password;
    public $confirm_password;
    public $status;
    public $date_created;
    public $profile_image;
    public $profile_image_id;
    public $role_name;
    public $role_permissions;
    public $role_id;
    /* Temp Location */
    public $contact_count;
    public $user_count;
    public $role_count;
    /* end Temp Location */
    protected $password_required = true;

    public function __construct($args = []) {
        $this->name_first = $args['name_first'] ?? '';
        $this->name_last = $args['name_last'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->username = $args['username'] ?? '';
        $this->status = $args['status'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->confirm_password = $args['confirm_password'] ?? '';
        $this->profile_image = $args['profile_image'] ?? '';
        $this->role_name = $args['role_name'] ?? '';
        $this->role_id = $args['role_id'] ?? '';
        $this->role_permissions = $args['role_permissions'] ?? '';
    }

    /**
     * Retrieves all stored details on all contacts
     * 
     * Similar to parent's findAll() method. The purpose behind the name being 
     * different is that it is incompatible with the parent's version of findAll()
     * in that getAll() uses a per_page and offset variable used for pagination.
     * The profile images are also stored in a separate table. The original findAll() is
     * expecting to find all details in one table, I imagine in true active record style.
     * 
     * @param int $per_page
     * @param int $offset
     * @return object array
     */
    public static function getAll($per_page, $offset) {
        $sql = 'SELECT 
            a.id, 
            a.role_id, 
            a.username, 
            a.name_first, 
            a.name_last, 
            a.hashed_password, 
            a.email, 
            a.status, 
            a.date_created,
            img.filename AS profile_image,
            r.role_name,
            img.id AS profile_image_id 
            FROM ' . static::$table_name . ' a
            LEFT JOIN ' . self::$image_xref . ' aix ON aix.user_id = a.id
            LEFT JOIN ab_images img ON aix.image_id = img.id
            LEFT JOIN ab_roles r ON a.role_id = r.role_id
            ORDER BY name_last ASC LIMIT ' . $per_page . ' OFFSET ' . self::quoteVal($offset);

        return static::findBySql($sql);
    }

    /**
     * Finds all details on a specific user
     * 
     * Selects all for a specific user, as did find_by_id, but also counts how many
     * blog posts this individual user has written, and includes the users profile
     * image name, if it exists, in the result set. If users.hashed_password is
     * not selected, it results in a thrown TypeError when attempting to update.
     */
    public static function findByUserId($id) {
        $sql = 'SELECT 
            a.id, 
            a.role_id, 
            a.username, 
            a.name_first, 
            a.name_last, 
            a.hashed_password, 
            a.email, 
            a.status, 
            a.date_created,
            img.filename AS profile_image,
            r.role_name,
            img.id AS profile_image_id 
            FROM ' . static::$table_name . ' a
            LEFT JOIN ' . self::$image_xref . ' aix ON aix.user_id = a.id
            LEFT JOIN ab_images img ON aix.image_id = img.id
            LEFT JOIN ab_roles r ON a.role_id = r.role_id
            WHERE a.id = ' . self::quoteVal($id);
        $obj_array = static::findBySql($sql);
        if (!empty($obj_array)):
            return array_shift($obj_array);
        else :
            return false;
        endif;
    }

    /**
     * Pulls a list of role names and their associated ID's from the database.
     * 
     * @return object
     */
    public static function getRoles() {
        $sql = 'SELECT role_id, role_name FROM ab_roles';
        return static::findBySql($sql);
    }

    /**
     * Counts Contacts, Admins, and Roles for display on the dashboard
     * 
     * It simply counts total Contacts and Registered users and displays
     * the count on the Dashboard Overview. Will likely re-locate this method.
     */
    public static function countDashboardItems() {
        $sql = 'SELECT '
                . '( SELECT COUNT(*) FROM ab_contacts ) AS contact_count, '
                . '( SELECT COUNT(*) FROM ab_admins ) AS user_count, '
                . '( SELECT COUNT(*) FROM ab_roles ) AS role_count';

        $obj_array = static::findBySql($sql);
        if (!empty($obj_array)):
            return array_shift($obj_array);
        else:
            return false;
        endif;
    }

    /**
     * Verifies user supplied password against what is stored.
     * 
     * @param string $password
     * @return boolean
     */
    public function verify_password($password) {
        return password_verify($password, $this->hashed_password);
    }

    /**
     * Hashes a password using PASSWORD_DEFAULT for storage as securely as possible.
     */
    protected function setHashedPassword() {
        $this->hashed_password = password_hash($this->password, PASSWORD_DEFAULT);
    }

    /**
     * Creates a new admin record
     * 
     * @return boolean
     */
    protected function create() {
        $this->setHashedPassword();
        return parent::create();
    }

    /**
     * Updates an existing admin record
     * 
     * @return boolean
     */
    protected function update() {
        switch ($this->password) :
            case '':
                # Password left blank. It is not being updated
                $this->password_required = false;
                break;
            default:
                # Password was not left blank. It is being updated - validate it
                $this->validatePassword();
                $this->setHashedPassword();
        endswitch;
        return parent::update();
    }

    /**
     * Performs admin validation when editing or adding a new user
     * 
     * @return array $this->errors[]
     */
    protected function validate($util = '\utility\Utility') {
        $this->errors = [];

        $this->errors = $this->validateFirstName($util);
        $this->errors = $this->validateLastName($util);
        $this->errors = $this->validateEmail($util);
        $this->errors = $this->validateUsername($util);
        $this->errors = $this->validatePassword($util);

        return $this->errors;
    }

    /**
     * Selects all on an existing username from the ab_admins table
     * 
     * If this method is being used at the login screen, pass boolean true in
     * as the second argument: <code>findByUsername('johnqpublic', true);</code>
     * 
     * Used in this way, the method only returns results if the user's status is
     * set to active (1). If inactive (0), the user is rewarded with an error
     * stating 'Invalid Credentials' and is unable to log into the system.
     * 
     * @param string $username
     * @param string $login
     * @return boolean
     */
    public static function findByUsername($username, $login = '') {
        switch ($login) {
            case true:
                $append = ' && status=1';
                break;
            default:
                $append = null;
        }
        $sql = "SELECT a.id, a.role_id, a.username, a.name_first, 
                a.name_last, a.hashed_password, a.email, a.status, 
                a.date_created, GROUP_CONCAT(rp.perm_id) AS role_permissions, r.role_name  
                FROM " . static::$table_name . " a, ab_roles_permissions rp, ab_roles r
                WHERE rp.role_id = a.role_id && a.role_id = r.role_id
                && username ='" . self::$database->escape_string($username) . "'$append
                HAVING a.id IS NOT NULL LIMIT 1";

        $obj_array = static::findBySql($sql);
        if (!empty($obj_array)) {
            return array_shift($obj_array);
        } else {
            return false;
        }
    }

    /**
     * Validates data exists for each required field
     * 
     * @param string $util
     * @return array
     */
    private function validateFirstName($util = '\utility\Utility') {
        if ($util::isBlank($this->name_first)) {
            $this->errors[] = 'First name cannot be blank.';
        } elseif (!$util::hasLength($this->name_first, ['min' => 2, 'max' => 255])) {
            $this->errors[] = 'First name must be between 2 and 255 characters.';
        } return $this->errors;
    }

    /**
     * Validates data exists for each required field
     * 
     * @param string $util
     * @return array
     */
    private function validateLastName($util = '\utility\Utility') {
        if ($util::isBlank($this->name_last)) {
            $this->errors[] = 'Last name cannot be blank.';
        } elseif (!$util::hasLength($this->name_last, ['min' => 2, 'max' => 255])) {
            $this->errors[] = 'Last name must be between 2 and 255 characters.';
        } return $this->errors;
    }

    /**
     * Validates data exists for each required field
     * 
     * @param string $util
     * @return array
     */
    private function validateEmail($util = '\utility\Utility') {
        if ($util::isBlank($this->email)) {
            $this->errors[] = 'Email cannot be blank.';
        } elseif (!$util::hasLength($this->email, array('max' => 255))) {
            $this->errors[] = 'Last name must be less than 255 characters.';
        } elseif (!$util::hasValidEmailFormat($this->email)) {
            $this->errors[] = 'Email must be a valid format.';
        } return $this->errors;
    }

    /**
     * Validates data exists for each required field
     * 
     * @param string $util
     * @return array
     */
    private function validateUsername($util = '\utility\Utility') {
        if ($util::isBlank($this->username)) {
            $this->errors[] = 'Username cannot be blank.';
        } elseif (!$util::hasLength($this->username, ['min' => 3, 'max' => 255])) {
            $this->errors[] = 'Username must be between 3 and 255 characters.';
        } elseif (!$util::hasUniqueUsername($this->username, $this->id ?? 0)) {
            $this->errors[] = 'Username unavailable. Try another.';
        } return $this->errors;
    }

    /**
     * Validates data exists for each required field
     * 
     * @param string $util
     * @return array
     */
    private function validatePassword($util = '\utility\Utility') {
        if ($this->password_required) {
            if ($util::isBlank($this->password)) {
                $this->errors[] = 'Password cannot be blank.';
            } if (!$util::hasLength($this->password, ['min' => 5])) {
                $this->errors[] = 'Password must contain 5 or more characters';
            } if (!preg_match('/[A-Z]/', $this->password)) {
                $this->errors[] = 'Password must contain at least 1 uppercase letter';
            } if (!preg_match('/[a-z]/', $this->password)) {
                $this->errors[] = 'Password must contain at least 1 lowercase letter';
            } if (!preg_match('/[0-9]/', $this->password)) {
                $this->errors[] = 'Password must contain at least 1 number';
            } if (!preg_match('/[^A-Za-z0-9\s]/', $this->password)) {
                $this->errors[] = 'Password must contain at least 1 symbol';
            }

            if ($util::isBlank($this->confirm_password)) {
                $this->errors[] = 'Confirm password cannot be blank.';
            } elseif ($this->password !== $this->confirm_password) {
                $this->errors[] = 'Password and confirm password must match.';
            }
        } return $this->errors;
    }

}
