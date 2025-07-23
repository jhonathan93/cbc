<?php

if (!function_exists('app')) {

    /**
     * @param string $class
     * @param ...$params
     *
     * @return object
     */
    function app(string $class, ...$params): object {
        return new $class(...$params);
    }
}

if (!function_exists('dd')) {

    /**
     * @param array $data
     *
     * @return void
     */
    function dd(array $data) {
        echo '<pre>';
        var_dump($data);
        echo '</pre>';
        die();
    }
}