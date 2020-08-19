<?php

namespace shared;

/**
 * Shared Profile
 * 
 * Provides a container in which to hold shared profile methods between
 * Contacts and Users
 * 
 * @category shared
 * @package address book
 * @author Paul Schudar
 * @copyright Copyright (c) 2020, Paul Schudar
 * @license https://opensource.org/licenses/mit-license.php MIT License
 */
trait Profile {

    /**
     * Prints the source for a contact's profile image
     * 
     * @return string
     */
    public function displayProfileImage($util = '\utility\Utility') {
        switch ($util::hasPresence($this->profile_image)) :
            case true:
                $image_source = $util::urlFor('/assets/images/profile/') . $this->profile_image;
                break;
            default:
                $image_source = $util::urlFor('/assets/images/profile/default.png');
        endswitch;
        return $image_source;
    }

    /**
     * Prints the number of records on display
     * 
     * @staticvar int $i
     * @return int
     */
    public static function count($i = 0) {
        static $i;
        ++$i;
        return $i;
    }

    /**
     * Returns the requested full name in one of two formats.
     * 
     * 'First Last' or 'Last, First'. $lastNameFirst = true for the latter.
     * $defaultName is used if both the first and last name are blank.
     * 
     * @param bool $lastNameFirst
     * @param string $defaultName
     * @return type
     */
    public function fullName(bool $lastNameFirst = false, string $defaultName = 'Anonymous') {
        switch ($lastNameFirst) :
            case true:
                return $this->lastNameFirst($defaultName);
            default:
                return $this->lastNameLast($defaultName);
        endswitch;
    }

    /**
     * Prints a contact's email address
     * 
     * @param string $index - true on index.php, false anywhere else
     * @return string
     */
    public function printEmail($index = true) {
        $email = null;
        if ($index) :
            $return = '--';
        else :
            $return = null;
        endif;
        if (\utility\Utility::hasPresence($this->email)) :
            $email .= \utility\Utility::h($this->email) . '<br>';
        else : $email = $return;
        endif;
        return $email;
    }

    /**
     * Displays administrative links to users authorized to use them.
     * 
     * To display View } Edit } Delete links for contacts, pass 
     * 'contacts' in as the first parameter for $module.
     * 
     * To display these links for 'admins', pass in 'admins' instead.
     *  
     * @param string $module
     * @param \session\Session $session
     * @return string
     */
    public function displayAdminLinks(string $module, \session\Session $session) {
        $read = $session->hasPermission($_SESSION['role_permissions'], 1);
        $update = $session->hasPermission($_SESSION['role_permissions'], 3);
        $delete = $session->hasPermission($_SESSION['role_permissions'], 2);

        return $this->buildAdminLinks($read, $update, $delete, $module);
    }

    /**
     * Formats the requested name in the desired way: LastName, FirstName
     * 
     * Used internally for the $this->fullName() method, lastNameFirst() checks
     * if both the first and last names have data present. If this condition is met,
     * the format of Last, First is pushed through the Utility:h() method, which 
     * simply runs the string through htmlspecialchars() and is then returned.
     *  
     * If at least one is empty/null, then the last name is checked for data being 
     * present. If it is not empty/null, then it is filtered with $util::h() and returned.
     * 
     * If the last name is empty/null, then the first name is tested for data being
     * present. If data exists within this variable, it is filtered and returned.
     * 
     * Otherwise, the final step is to then infer that the name is completely blank
     * and finally, assign a default name. Please see $this->fullName() for example usage.
     * 
     * @param string $defaultName
     * @param string $util
     * @return string
     */
    private function lastNameFirst(string $defaultName, string $util = '\utility\Utility') {
        if ($util::hasPresence($this->name_last) && $util::hasPresence($this->name_first)) :
            return $util::h($this->name_last . ", " . $this->name_first);

        elseif ($util::hasPresence($this->name_last)) :
            return $util::h($this->name_last);

        elseif ($util::hasPresence($this->name_first)) :
            return $util::h($this->name_first);

        else :
            return $defaultName;
        endif;
    }

    /**
     * Formats the requested name in the desired way: FirstName LastName
     * 
     * Used internally for the $this->fullName() method, lastNameLast() checks
     * if both the first and last names have data present. If this condition is met,
     * the format of FirstName LastName is pushed through the Utility:h() method, which 
     * simply runs the string through htmlspecialchars() and is then returned.
     *  
     * If at least one is empty/null, then the first name is checked for data being 
     * present. If it is not empty/null, then it is filtered with $util::h() and returned.
     * 
     * If the first name is empty/null, then the last name is tested for data being
     * present. If data exists within this variable, it is filtered and returned.
     * 
     * Otherwise, the final step is to then infer that the name is completely blank
     * and finally, assign a default name. Please see $this->fullName() for example usage.
     * 
     * @param string $defaultName
     * @param string $util
     * @return string
     */
    private function lastNameLast(string $defaultName, string $util = '\utility\Utility') {
        if ($util::hasPresence($this->name_first) && $util::hasPresence($this->name_last)) :
            return $util::h($this->name_first . ' ' . $this->name_last);

        elseif ($util::hasPresence($this->name_first)) :
            return $util::h($this->name_first);

        elseif ($util::hasPresence($this->name_last)) :
            return $util::h($this->name_last);

        else :
            return $defaultName;
        endif;
    }

    /**
     * Builds administrative links: View | Edit | Delete
     * 
     * If a user has the proper permissions to view the page that the link leads to, 
     * the link is displayed. Otherwise, the link does not appear in the DOM. This is 
     * considered a convenience method as even if the links were to appear, the actions
     * are restricted to only those users with the corresponding roles.
     * 
     * @param bool $read
     * @param bool $update
     * @param bool $delete
     * @param string $util
     * @return string
     */
    private function buildAdminLinks($read, $update, $delete, $module, $util = '\utility\Utility') {
        $linkSet = null;
        $pipe = '<span class="text-muted">&#124;</span>';
        $mod = $util::h($module);

        $linkSet .= $this->buildReadLink($read, $mod, $util);
        $linkSet .= $this->buildUpdateLink($update, $pipe, $mod, $util);
        $linkSet .= $this->buildDeleteLink($delete, $pipe, $mod, $util);

        return $linkSet;
    }

    /**
     * Given the proper permissions, this method returns a 'View' link for the specified module
     * 
     * @param bool $read
     * @param string $module
     * @param string $util
     * @return string
     */
    private function buildReadLink(bool $read, string $module, string $util) {
        switch ($read) {
            case true:
                $link = '<a href="' . $util::urlFor('/staff/' . $module . '/view.php?id=') . $util::hu($this->id) . '">View</a>';
                return $link;
        } return;
    }

    /**
     * Given the proper permissions, this method returns an 'Edit' link for the specified module
     * 
     * @param bool $update
     * @param string $pipe
     * @param string $module
     * @param string $util
     * @return string
     */
    private function buildUpdateLink(bool $update, string $pipe, string $module, string $util) {
        switch ($update) {
            case true:
                $link = ' ' . $pipe . '  <a href="' . $util::urlFor('/staff/' . $module . '/edit.php?id=') . $util::hu($this->id) . '">Edit</a>';
                return $link;
        } return;
    }

    /**
     * Given the proper permissions, this method returns a 'Delete' link for the specified module
     * 
     * @param type $delete
     * @param type $pipe
     * @param type $module
     * @param type $util
     * @return string
     */
    private function buildDeleteLink(bool $delete, string $pipe, string $module, string $util) {
        switch ($module) {
            case 'contacts':
                $target = '#deleteContact';
                break;
            case 'admins':
                $target = '#deleteContact';
                break;
        }
        switch ($delete) {
            case true:
                $link = ' ' . $pipe . ' <a data-id="' . $util::hu($this->id) . '" data-name="' . $util::h($this->fullName()) . '" data-toggle="modal" data-target="' . $target . '" class="triggerDelete" href="' . $util::urlFor('/staff/' . $module . '/delete.php?id=') . $util::hu($this->id) . '">Delete</a>';
                return $link;
        } return;
    }

}
