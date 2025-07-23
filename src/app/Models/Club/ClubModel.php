<?php

namespace app\Models\Club;

use app\Models\BaseModel;
use Exception;

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
     * @param int $id
     * @return mixed|null
     */
    public function getById(int $id) {
        return $this->db->table($this->table)->where('id', '=', $id)->first();
    }

    /**
     * @param array $data
     *
     * @return bool
     */
    public function create(array $data): bool {
        return $this->db->table($this->table)->insert($data);
    }
}