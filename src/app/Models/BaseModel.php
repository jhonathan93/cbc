<?php

namespace app\Models;

use Database;

class BaseModel {

    /**
     * @var Database
     */
    protected $db;

    /**
     * @var string
     */
    protected $table;

    public function __construct() {
        $this->db = new Database();
    }
}