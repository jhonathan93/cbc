<?php

namespace app\Models\Resource;

use Exception;
use app\Models\BaseModel;

class ResourceModel extends BaseModel {

    /**
     * @var string
     */
    protected $table = 'recurso';

    /**
     * @return array
     *
     * @throws Exception
     */
    public function get(): array {
        return $this->db->table($this->table)->get();
    }
}