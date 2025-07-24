<?php

namespace app\Service\Consumption;

use Database;
use Exception;
use app\Models\Club\ClubModel;
use app\Models\Resource\ResourceModel;

class ConsumptionService {

    public function __construct() {}

    /**
     * @param ClubModel $club
     * @param ResourceModel $resource
     * @param float $value
     * @return array
     *
     * @throws Exception
     */
    public static function consume(ClubModel $club, ResourceModel $resource, float $value): array {
        try {
            if ($club->saldo_disponivel === 0.0 || $club->saldo_disponivel < $value ) throw new Exception("O saldo disponível do clube é insuficiente.");

            if ($resource->saldo_disponivel === 0.0 || $resource->saldo_disponivel < $value) throw new Exception("O saldo disponível do recurso é insuficiente.");

            $id = $club->id;
            $previousBalance = $club->saldo_disponivel;

            /**
             * Está logica de beginTransaction so é possível porque a instância da classe
             * foi desenvolvida usando o padrão de projeto singleton {https://refactoring.guru/pt-br/design-patterns/singleton/php/example}
             * que garante que apenas um objeto desse tipo exista
             */
            Database::getInstance()->transaction(function() use ($club, $resource, $value) {
                $club->update(['saldo_disponivel' => $club->saldo_disponivel - $value]);
                $resource->update(['saldo_disponivel' => $resource->saldo_disponivel - $value]);
            });

            $club->load($id);

            return [
                'clube' => $club->clube,
                'saldo_anterior' => $previousBalance,
                'saldo_atual' => $club->saldo_disponivel,
            ];
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}