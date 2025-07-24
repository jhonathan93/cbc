<?php

namespace app\Models\Resource;

use Exception;
use app\Models\BaseModel;

/**
 * @property int $id
 * @property string $recurso
 * @property float $saldo_disponivel
 */
class ResourceModel extends BaseModel {

    /**
     * @var string
     */
    protected $table = 'recurso';

    /**
     * @var array
     */
    protected $columns = [
        "id",
        "recurso",
        "saldo_disponivel"
    ];

    /**
     * @return array
     * @throws Exception
     */
    public function getData(): array {
        return $this->db->table($this->table)->get();
    }

    /**
     * @param int $id
     *
     * @return self
     * @throws Exception
     */
    public function getObject(int $id): self {
        if (!$this->load($id)) throw new Exception("$this->table não localizado!");

        return $this;
    }

    /**
     * @param array $data
     *
     * @return bool
     * @throws Exception
     */
    public function update(array $data): bool {
        return $this->db->table($this->table)->where('id', '=', $this->id)->update($data);
    }
}