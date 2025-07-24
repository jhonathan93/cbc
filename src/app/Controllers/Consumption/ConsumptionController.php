<?php

namespace app\Controllers\Consumption;

use Response;
use Exception;
use app\Models\Club\ClubModel;
use app\Controllers\BaseController;
use app\Models\Resource\ResourceModel;
use app\Service\Consumption\ConsumptionService;

class ConsumptionController extends BaseController {

    public function __construct() {}

    /**
     * @return void
     */
    public function consume() {
        try {
            $data = $this->getRequestBody();

            $this->validateRequest([
                "clube_id" => 'required|numeric',
                "recurso_id" => 'required|numeric',
                "valor_consumo" => 'required|positive_numeric',
            ]);

            $club = app(ClubModel::class)->getObject($data['clube_id']);
            $resource = app(ResourceModel::class)->getObject($data['recurso_id']);

            Response::send(200, ConsumptionService::consume($club, $resource, $data['valor_consumo']));
        } catch (Exception $e) {
            Response::error($e->getMessage());
        }
    }
}