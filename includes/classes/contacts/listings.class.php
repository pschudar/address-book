<?php

namespace contacts;

/**
 * Listings creates, reads, updates, and deletes Blog Categories.
 * 
 * Uses the infrastructure from DatabaseObject class to perform CRUD actions
 */
class Listings extends \database\DatabaseObject {

    use \shared\Profile;

    static protected $table_name = 'ab_contacts';
    static protected $image_xref = 'ab_image_xref';
    static protected $db_columns = ['name_first', 'name_last', 'email', 'address', 'address2', 'city', 'state', 'zip', 'phone_home', 'phone_mobile', 'date_of_birth'];
    public $id;
    public $profile_image;
    public $profile_image_id;
    public $name_first;
    public $name_last;
    public $email;
    public $address;
    public $address2;
    public $city;
    public $state;
    public $zip;
    public $phone_home;
    public $phone_mobile;
    public $date_of_birth;
    public $date_created;
    public $error;

    /**
     * The constructor defines the properties upon instantiation 
     * 
     * The value is the value posted on the form, or blank if left empty.
     * 
     * @param array $args
     */
    public function __construct($args = []) {
        $this->profile_image = $args['profile_image'] ?? '';
        $this->name_first = $args['name_first'] ?? '';
        $this->name_last = $args['name_last'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->address = $args['address'] ?? '';
        $this->address2 = $args['address2'] ?? '';
        $this->city = $args['city'] ?? '';
        $this->state = $args['state'] ?? '';
        $this->zip = $args['zip'] ?? '';
        $this->phone_home = $args['phone_home'] ?? '';
        $this->phone_mobile = $args['phone_mobile'] ?? '';
        $this->date_of_birth = $args['date_of_birth'] ?? '';
    }

    /**
     * Runs digits through a filter
     * 
     * Removes dashes and filters the number as an int 
     * Uses FILTER_SANITIZE_NUMBER_INT which is ID 519
     * Finally, runs the filtered number through htmlspecialchars()
     * 
     * @param int $number
     * @return int|string
     */
    public static function filterNumber($number) {
        switch (\utility\Utility::hasPresence($number)) {
            case true:
                $noHyphen = str_replace('-', '', $number);
                $numberFiltered = filter_var($noHyphen, 519);
                return \utility\Utility::h($numberFiltered);
            default:
                $default = '--';
                return $default;
        }
    }

    /**
     * Prints the contact's address
     * 
     * @return string
     */
    public function printAddress() {
        $address = null;
        if (\utility\Utility::hasPresence($this->address)) {
            $address .= $this->address . ' <br>';
        }
        if (\utility\Utility::hasPresence($this->address2)) {
            $address .= $this->address2 . ' <br>';
        }
        if (\utility\Utility::hasPresence($this->city)) {
            $address .= $this->city . ', ';
        }
        if (\utility\Utility::hasPresence($this->state)) {
            $address .= $this->state . ' ';
        }
        if (\utility\Utility::hasPresence($this->state)) {
            $address .= $this->zip . '<br>';
        }
        return $address;
    }

    /**
     * Prints the contact's phone numbers as a telephone link.
     * 
     * @return string
     */
    public function printPhoneNumbers() {
        $phone = null;
        if (\utility\Utility::hasPresence($this->phone_home)) {
            $phone .= 'Home: <a href="tel:+1' . $this->phone_home . '">' . $this->phone_home . '</a><br>';
        }
        if (\utility\Utility::hasPresence($this->phone_mobile)) {
            $phone .= 'Mobile: <a href="tel:+1' . $this->phone_mobile . '">' . $this->phone_mobile . '</a><br>';
        }
        return $phone;
    }

    /**
     * Finds all details on a specific contact
     * 
     * Selects all for a specific user, as did findById, but also includes
     * the contact's profile image name and image ID, if they exist, in the result set.
     */
    public static function findByContactId($id) {
        $sql = 'SELECT c.id,
      c.name_first, c.name_last,
      c.email, c.address,
      c.address2, c.city,
      c.state, c.zip,
      c.phone_home, c.phone_mobile,
      c.date_of_birth, c.date_created,
      img.filename AS profile_image,
      img.id AS profile_image_id
      FROM ab_contacts c
      LEFT JOIN ' . self::$image_xref . ' cix ON cix.contact_id = c.id
      LEFT JOIN ab_images img ON cix.image_id = img.id
      WHERE c.id = ' . self::quoteVal($id);
        $obj_array = static::findBySql($sql);
        if (!empty($obj_array)):
            return array_shift($obj_array);
        else :
            return false;
        endif;
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
        $sql = 'SELECT c.id,
      c.name_first, c.name_last,
      c.email, c.address,
      c.address2, c.city,
      c.state, c.zip,
      c.phone_home, c.phone_mobile,
      c.date_of_birth, c.date_created,
      img.filename AS profile_image,
      img.id AS profile_image_id
      FROM ' . static::$table_name . ' c
      LEFT JOIN ' . self::$image_xref . ' cix ON cix.contact_id = c.id
      LEFT JOIN ab_images img ON cix.image_id = img.id
      ORDER BY name_last ASC LIMIT ' . $per_page . ' OFFSET ' . self::quoteVal($offset);

        return static::findBySql($sql);
    }

    public function formatAge($dob) {
        $date = \utility\Utility::h($dob);
        if (strpos('0000-00-00', $date) !== false) {
            return '--';
        }
        # calculate years of age (input string: YYYY-MM-DD)
        list($year, $month, $day) = explode('-', $date);

        $yearDiff = date('Y') - $year;
        $monthDiff = date('m') - $month;
        $dayDiff = date('d') - $day;

        # if we are any month before the birthdate: year - 1 
        # OR if we are in the month of birth but on a day 
        # before the actual birth day: year - 1
        if (($monthDiff < 0 ) || ($monthDiff === 0 && $dayDiff < 0)) {
            $yearDiff--;
        }

        return $yearDiff;
    }

    public function dateOfBirth() {
        if ($this->date_of_birth === '0000-00-00') {
            $dateOfBirth = null;
        } else {
            $dateOfBirth = \utility\Utility::h($this->date_of_birth);
        }
        return $dateOfBirth;
    }

}
