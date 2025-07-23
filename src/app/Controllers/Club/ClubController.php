<?php

namespace app\Controllers\Club;

use Response;
use Exception;
use app\Models\Club\ClubModel;
use app\Controllers\BaseController;

class ClubController extends BaseController {

    public function __construct() {}

    /**
     * @return void
     */
    public function getClubs() {
        try {
            Response::send(200, app(ClubModel::class)->get());
        } catch (Exception $e) {
            Response::error($e->getMessage());
        }
    }

    /**
     * @return void
     */
    public function createClub() {
        try {
            $data = $this->getRequestBody();

            $this->validateRequest([
                "clube" => 'required|string',
                "saldo" => 'required|positive_numeric',
            ]);

            if (!app(ClubModel::class)->create($data)) throw new Exception("Estamos tendo problemas");

            Response::success("ok");
        } catch (Exception $e) {
            Response::error($e->getMessage());
        }
    }
}