<?php

namespace app\Models\club;

use app\Models\BaseModel;

class ClubModel extends BaseModel {

    /**
     * @var string
     */
    protected $table = 'clubes';

    /**
     * @return string
     */
    public function get() {
        return $this->table;
    }
}