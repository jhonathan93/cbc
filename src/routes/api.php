<?php

use app\Controllers\Club\ClubController;
use app\Controllers\Resource\ResourceController;
use app\Controllers\Consumption\ConsumptionController;

class ApiRouter {

    /**
     * @var array
     */
    private $routes;

    public function __construct() {
        $this->initializeRoutes();
    }

    /**
     * @return void
     */
    private function initializeRoutes() {
        $this->routes = [
            'GET' => [
                '/clube' => [ClubController::class, 'getClubs'],
                '/recurso' => [ResourceController::class, 'getResources'],
            ],
            'POST' => [
                '/clube' => [ClubController::class, 'createClub'],
                '/consumir' => [ConsumptionController::class, 'consume'],
            ]
        ];
    }

    /**
     * @param string $method
     * @param string $uri
     *
     * @return void
     */
    public function handleRequest(string $method, string $uri) {
        $method = strtoupper($method);
        $uri = $this->normalizeUri($uri);

        try {
            $this->dispatch($method, $uri);
        } catch (Exception $e) {
            Response::send(500, ['message' => $e->getMessage()]);
        }
    }

    /**
     * @param string $method
     * @param string $uri
     *
     * @return void
     */
    private function dispatch(string $method, string $uri) {
        if (!isset($this->routes[$method])) Response::send(405, ['message' => 'Método não permitido']);

        foreach ($this->routes[$method] as $route => $handler) {
            if ($this->matchRoute($route, $uri)) {
                list($controllerClass, $methodName) = $handler;
                $controller = new $controllerClass();
                $controller->$methodName();
                return;
            }
        }

        Response::send(404, ['message' => 'Rota não localizada!']);
    }

    /**
     * @param string $route
     * @param string $uri
     *
     * @return bool
     */
    private function matchRoute(string $route, string $uri): bool {
        $routePattern = preg_replace('/\//', '\/', $route);
        $routePattern = '/^' . $routePattern . '(\/?)$/';

        return (bool) preg_match($routePattern, $uri);
    }

    /**
     * @param string $uri
     *
     * @return string
     */
    private function normalizeUri(string $uri): string {
        return '/' . trim($uri, '/');
    }
}