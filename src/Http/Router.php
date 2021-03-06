<?php

namespace Expr\Http;

use Closure;

class Router
{
    private bool $route_matched = false;
    private Request $request;
    private Response $response;

    /**
     * @param string $uri
     * @param Closure[] $action
     */
    private function execute(string $uri, ...$action): void {
        $this->request = new Request();
        $this->response = new Response();

        $params = $this->matchRoute($uri, $this->request->getParams());

        if ($params === null) {
            return;
        }

        $this->route_matched = true;
        $this->request->setParams($params);

        foreach ($action as $controller) {
            if (!$controller instanceof Closure) {
                return;
            }
            $controller($this->request, $this->response);
            if (!$this->request->didNextCalled()) {
                return;
            }
            $this->request->resetNext();
        }
    }

    /**
     * @param string $uri
     * @param Closure ...$action
     */
    public function get(string $uri, ...$action): void
    {
        if ($this->route_matched || !isset($_SERVER['REQUEST_METHOD']) || $_SERVER['REQUEST_METHOD'] !== 'GET') {
            return;
        }

        $this->execute($uri, ...$action);
    }


    /**
     * @param string $uri
     * @param Closure ...$action
     */
    public function post(string $uri, ...$action): void
    {
        if ($this->route_matched || !isset($_SERVER['REQUEST_METHOD']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $this->execute($uri, ...$action);
    }

    /**
     * @param string $uri
     * @param Closure ...$action
     */
    public function put(string $uri, ...$action): void
    {
        if ($this->route_matched || !isset($_SERVER['REQUEST_METHOD']) || $_SERVER['REQUEST_METHOD'] !== 'PUT') {
            return;
        }

        $this->execute($uri, ...$action);
    }

    /**
     * @param string $uri
     * @param Closure ...$action
     */
    public function delete(string $uri, ...$action): void
    {
        if ($this->route_matched || !isset($_SERVER['REQUEST_METHOD']) || $_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            return;
        }

        $this->execute($uri, ...$action);
    }

    /**
     * @param string $uri
     * @param Closure ...$action
     */
    public function patch(string $uri, ...$action): void
    {
        if ($this->route_matched || !isset($_SERVER['REQUEST_METHOD']) || $_SERVER['REQUEST_METHOD'] !== 'PATCH') {
            return;
        }

        $this->execute($uri, ...$action);
    }

    /**
     * @param string $uri
     * @param Closure ...$action
     */
    public function any(string $uri, ...$action): void
    {
        if ($this->route_matched) {
            return;
        }

        $this->execute($uri, ...$action);
    }

    public function matchRoute(string $path, array $request_path_array): ?array
    {
        if ($path === '*') {
            return [];
        }

        $path_array = $this->pathToArray($path);

        if (count($path_array) !== count($request_path_array)) {
            return null;
        }

        return $this->parseRoute($path_array, $request_path_array);
    }

    public function parseRoute(array $path_array, array $request_path_array): ?array
    {
        $parsed_route = [];

        foreach ($path_array as $key => $item) {
            $new_key = ltrim($item, ':');

            $parsed_route[$new_key] = (strpos($item, ':') === 0) ? $request_path_array[$key] : null;

            if ($parsed_route[$new_key] === null && $item !== $request_path_array[$key]) {
                return null;
            }
        }

        return $parsed_route;
    }

    /**
     * @param string $path
     * @return string[]
     */
    public function pathToArray(string $path): array
    {
        return explode('/', ltrim($path, '/'));
    }

    public function listen(): void
    {
        if (empty($this->request) || empty($this->response)) {
            http_response_code(500);
            $this->request = new Request();
            echo "Cannot {$this->request->getMethod()} /";
            return;
        }

        echo $this->response->getBody();
    }
}