<?php

declare(strict_types=1);

namespace user;

class Role extends \database\DatabaseObject {

    use \shared\Profile;

    static protected $table_name = 'ab_roles';
    static protected $db_columns = ['role_id', 'role_name'];
    public $role_id;
    public $role_name;
    public $perm_desc;

    /**
     * Pulls a list of role names and their associated ID's from the database.
     * 
     * @return object
     */
    public static function getAll($per_page, $offset) {
        $sql = 'SELECT role_id, role_name FROM ab_roles LIMIT ' . $per_page . ' OFFSET ' . self::quoteVal($offset);
        return static::findBySql($sql);
    }

    public static function getRolePermissions($role_id) {
        $sql = 'SELECT 
            ab_permissions.* FROM ab_permissions
            JOIN ab_roles_permissions
            ON ab_permissions.perm_id = ab_roles_permissions.perm_id
            WHERE ab_roles_permissions.role_id=' . self::quoteVal($role_id);
        return static::findBySql($sql);
    }

    public static function getRoleHeader($role_id) {
        switch ($role_id) {
            case 1:
            case 2:
            case 3:
            case 4:
                $card_header = 'Contacts';
                break;
            case 5:
            case 6:
            case 7:
            case 8:
                $card_header = 'Admins';
                break;
            case 9:
            case 10:
            case 11:
            case 12:
                $card_header = 'Roles';
                break;
        } return $card_header;
    }

}
