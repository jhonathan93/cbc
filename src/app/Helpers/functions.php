<?php

if (!function_exists('app')) {

    /**
     * @param string $class
     * @param ...$params
     *
     * @return object
     */
    function app($class, ...$params) {
        return new $class(...$params);
    }
}

if (!function_exists('dd')) {
    function dd($data) {
        echo '<pre>';
        var_dump($data);
        echo '</pre>';
        die();
    }
}