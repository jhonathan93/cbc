<?php

namespace app\Controllers;

use Exception;
use Validator;

class BaseController {

    /**
     * @return array
     */
    protected function getRequestBody(): array {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        $input = file_get_contents('php://input');

        if (strpos($contentType, 'application/json') !== false) return json_decode($input, true) ?? [];

        if (strpos($contentType, 'application/x-www-form-urlencoded') !== false || strpos($contentType, 'multipart/form-data') !== false) return $_POST;

        return [];
    }

    /**
     * @param array $rules
     *
     * @return array
     * @throws Exception
     */
    protected function validateRequest(array $rules): array {
        $data = $this->getRequestBody();
        $this->validate($data, $rules);
        return $data;
    }

    /**
     * @param array $data
     * @param array $rules
     * @throws Exception
     */
    private function validate(array $data, array $rules): void {
        $validator = new Validator();
        $validator->validate($data, $rules);
    }
}