<?php

namespace app\Controllers\Resource;

use Response;
use Exception;
use app\Controllers\BaseController;
use app\Models\Resource\ResourceModel;

class ResourceController extends BaseController {

    public function __construct() {}

    /**
     * @return void
     */
    public function getResources() {
        try {
            Response::send(200, app(ResourceModel::class)->get());
        } catch (Exception $e) {
            Response::error($e->getMessage());
        }
    }
}