<?php

namespace app\Models\Resource;

use app\Models\BaseModel;

class ResourceModel extends BaseModel {

    /**
     * @var string
     */
    protected $table = 'recurso';

    /**
     * @return array
     */
    public function get() {
        return $this->db->table($this->table)->get();
    }
}