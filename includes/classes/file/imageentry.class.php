<?php

namespace file;

/**
 * ImageEntry extends DatabaseObject
 * 
 * In this way, the class inherits all of the necessary CRUD features of 
 * the parent class.
 */
class ImageEntry extends \database\DatabaseObject {

    static protected $table_name = 'ab_images';
    static protected $db_columns = ['id', 'filename'];
    public $id;
    public $filename;

    public function __construct($args = []) {
        $this->filename = $args['filename'] ?? '';
    }

}
