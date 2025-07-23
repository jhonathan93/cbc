<?php

namespace app\Controllers\Club;

use app\Models\Club\ClubModel;
use app\Controllers\BaseController;

class ClubController extends BaseController {
    public function getClubs() {
        \Response::send(200, app(ClubModel::class)->get());
    }

    public function createClubs() {
        // sua implementação aqui
    }
}