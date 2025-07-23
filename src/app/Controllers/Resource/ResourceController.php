<?php

namespace app\Controllers\Resource;

use app\Controllers\BaseController;
use app\Models\Resource\ResourceModel;

class ResourceController extends BaseController {

    public function __construct() {}

    public function getResources() {
        \Response::send(200, app(ResourceModel::class)->get());
    }
}