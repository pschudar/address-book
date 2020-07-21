<?php

declare(strict_types=1);

namespace database;

class DatabaseObject {

    use \utility\Debug;

    /**
     * database name
     * 
     * @var string
     */
    static protected $database;

    /**
     * table name that belongs to $database
     * 
     * @var string
     */
    static protected $table_name = '';

    /**
     * An array of column names
     * 
     * @var array 
     */
    static protected $columns = [];

    /**
     * An array of error messages
     * 
     * @var array
     */
    public $errors = [];

    /**
     * A flag to allow monitoring of the class behavior
     * 
     * It is used in conjunction with the static method console_log.
     * 
     * @var boolean 
     */
    public static $debug = false;

    /**
     * Sets the database name to be used
     * 
     * @param string $database
     */
    public static function setDatabase($database) {
        self::$database = $database;
    }

    /**
     * Returns last mysqli error
     * 
     * @return string
     */
    public static function getLastError() {
        return static::$database->error;
    }

    /**
     * Runs an SQL query and returns an object array containing the data rows
     * 
     * @param string $sql
     * @return object
     */
    public static function findBySql($sql) {
        $result = self::$database->query($sql);
        if (!$result) :
            # A generic message for production. Hopefully nobody ever sees it.
            // exit('Database query failed. Please inform the site admin.');
            # A more in depth explanation - useful for debugging.
            self::debug($sql);
            exit('<h3>Database query failed.</h3> <h4>Reason: ' . self::$database->error . ".</h4> <h5>SQL Statement: $sql</h5>");
        endif;
        self::debug('Table: ' . static::$table_name . ' SQL: ' . $sql);
        # result to object
        $object_array = [];
        while ($record = $result->fetch_assoc()) :
            $object_array[] = static::instantiate($record);
        endwhile;

        $result->free();

        return $object_array;
    }

    /**
     * Finds all records
     * 
     * Uses static to determine calling subclass at run time
     */
    public static function findAll() {
        $sql = 'SELECT * FROM ' . static::$table_name;
        return static::findBySql($sql);
    }

    /**
     * Counts all records in a table
     * 
     * Uses late static binding to determine which class called the method and
     * counts the records in the child classes $table_name variable.
     * 
     * @return int
     */
    public static function countAll() {
        $sql = 'SELECT COUNT(*) FROM ' . static::$table_name;
        $result_set = self::$database->query($sql);
        $row = $result_set->fetch_array();
        return array_shift($row);
    }

    /**
     * find_by_id allows a user to find a specific record using the record's ID number
     * 
     * In many cases an 'id' isn't always titled 'id'. It may be 'user_id' or 'image_id', etc.
     * In this instance, pass in the table column title as the second parameter. If it's just 'id' 
     * then there's no need to use the second argument.
     * @param int $id
     * @param string $id_title
     * @return object array or boolean
     */
    public static function findById($id, $id_title = 'id') {
        $sql = 'SELECT * FROM ' . static::$table_name . ' ';
        #$sql .= "WHERE " . $id_title . "='" . self::$database->escape_string($id) . "'";
        $sql .= "WHERE " . $id_title . "='" . self::quoteVal($id) . "'";
        $obj_array = static::findBySql($sql);
        if (!empty($obj_array)):
            return array_shift($obj_array);
        else:
            return false;
        endif;
    }

    /**
     * Creates or Updates a record
     * 
     * New records will not have an ID. If an ID is not present when called,
     * the create() method is used. Otherwise, the update() method is used.
     * 
     * @return type
     */
    public function save() {
        switch (isset($this->id)) :
            case false:
                return $this->create();
            case true:
                return $this->update();
        endswitch;
    }

    /**
     * Assigns key / value pairs updated values during an update
     * 
     * @param array $args
     */
    public function mergeAttributes($args = []) {
        foreach ($args as $key => $value) {
            if (property_exists($this, $key) && !is_null($value)) {
                $this->$key = $value;
            }
        }
    }

    /**
     * Defines property values based on db_columns in its respective class
     * 
     * Excludes 'id'
     * @return array
     */
    public function attributes() {
        $attributes = [];
        foreach (static::$db_columns as $column) {
            if ($column == 'id') {
                continue;
            }
            $attributes[$column] = $this->$column;
        }
        return $attributes;
    }

    /**
     * Deletes a specified record from the database
     * 
     * Not all 'id' columns are the same. They don't always get defined simply as 'id'
     * If there is a 'blog_id' instead, pass in 'blog_id' as the title. If the 'id' column
     * is truly titled 'id', $id_title is optional and can be left out as it defaults to 'id'.
     * -- Notes --
     * After deleting, the instance of the object will still exist in memory, even though
     * the actual database record does not. This can prove to be useful. See this example:
     * <code>echo $user->first_name . " was deleted.";</code>
     * We cannot call <code>$admin->ssve();</code> after calling <code>$admin->delete();</code> 
     * @param string $id_title
     * @return array
     */
    public function delete($id_title = 'id') {
        $sql = "DELETE FROM " . static::$table_name . " ";
        $sql .= "WHERE " . $id_title . "='" . self::quoteVal($this->$id_title) . "' ";
        $sql .= "LIMIT 1";
        $result = self::$database->query($sql);
        self::debug($sql);
        return $result;
    }

    /**
     * Loops through columns, if property exists, the value is assigned
     * 
     * If the property does not exist in db_columns, then it is skipped.
     * Handles the process of converting the values in the row into a 
     * new object with properties that have the same value.
     * @param type $record
     * @return \static
     */
    protected static function instantiate($record) {
        $object = new static;
        foreach ($record as $property => $value) {
            if (property_exists($object, $property)) {
                $object->$property = $value;
            }
        }
        return $object;
    }

    /**
     * A shell method - returns a stack of error messages.
     * 
     * @return array
     */
    protected function validate() {
        $this->errors = [];

        # Add custom validations

        return $this->errors;
    }

    /**
     *  Creates a new record.
     * 
     * Do not call create() directly. Instead, use save().
     * 
     * @return boolean
     */
    protected function create() {
        $this->validate();
        if (!empty($this->errors)) {
            return false;
        }

        $attributes = $this->sanitizedAttributes();
        $sql = "INSERT INTO " . static::$table_name . " (";
        $sql .= join(', ', array_keys($attributes));
        $sql .= ") VALUES ('";
        $sql .= join("', '", array_values($attributes));
        $sql .= "')";
        self::debug($sql);
        $result = self::$database->query($sql);
        if ($result) {
            $this->id = self::$database->insert_id;
            return $result;
        } else {
            // Adds the last error MySQL / MariaDB encountered to the property error
            $this->error = self::$database->error;
        }
        // Tacks the error onto the errors array
        return $this->errors[] = $this->error;
    }

    /**
     * Updates an existing database record. 
     * 
     * Do not call update() directly. Instead, use save().
     * 
     * @return boolean
     */
    protected function update() {
        $this->validate();
        if (!empty($this->errors)) {
            return false;
        }

        $attributes = $this->sanitizedAttributes();
        $attribute_pairs = [];
        foreach ($attributes as $key => $value) {
            $attribute_pairs[] = "{$key}='{$value}'";
        }

        $sql = "UPDATE " . static::$table_name . " SET ";
        $sql .= join(', ', $attribute_pairs);
        $sql .= " WHERE id='" . self::quoteVal($this->id) . "' ";
        $sql .= "LIMIT 1";
        $result = self::$database->query($sql);
        self::debug($sql);
        return $result;
    }

    /**
     * A utility method used to filter variables
     * 
     * @return array
     */
    protected function sanitizedAttributes() {
        $sanitized = [];
        foreach ($this->attributes() as $key => $value) {
            $sanitized[$key] = self::quoteVal($value);
        }
        return $sanitized;
    }

    /**
     * A utility method used to run strings through mysqli_real_escape_string()
     * 
     * This method accepts a string and returns another with special characters
     * backslashed. Numbers are quoted then run through _real_escape_string.
     * 
     * @param string|int $val
     * @return string|int
     */
    protected static function quoteVal($val) {
        if (is_null($val)) :
            return;
        elseif (is_numeric($val)) :
            return self::$database->escape_string("$val");
        else :
            return self::$database->escape_string($val);
        endif;
    }

}
