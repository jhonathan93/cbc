<?php

namespace app\Models\Club;

use Exception;
use app\Models\BaseModel;

class ClubModel extends BaseModel {

    /**
     * @var string
     */
    protected $table = 'clubes';

    /**
     * @return array
     *
     * @throws Exception
     */
    public function get(): array {
        return $this->db->table($this->table)->get();
    }

    /**
     * @param array $data
     * @return bool
     *
     * @throws Exception
     */
    public function create(array $data): bool {
        return $this->db->table($this->table)->insert($data);
    }
}