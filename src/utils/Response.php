<?php

class Response {

    /**
     * @param int $statusCode
     * @param mixed $data
     * @param array $headers
     */
    public static function send(int $statusCode, $data = [], array $headers = []) {
        http_response_code($statusCode);

        header('Content-Type: application/json; charset=utf-8');

        foreach ($headers as $header => $value) {
            header("$header: $value");
        }

        $jsonOptions = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES;

        echo json_encode($data, $jsonOptions);
        exit;
    }

    /**
     * @param string $message
     * @param int $statusCode
     *
     * @return void
     */
    public static function success(string $message = '', int $statusCode = 200) {
        self::send($statusCode, $message);
    }

    /**
     * @param string $message
     * @param int $statusCode
     *
     * @return void
     */
    public static function error(string $message, int $statusCode = 400) {
        self::send($statusCode, ['message' => $message]);
    }
}