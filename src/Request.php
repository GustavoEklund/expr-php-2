<?php

namespace Expr;

use JsonException;
use RuntimeException;

/**
 * Class Request
 * @package Expr
 */
class Request
{
    private array $body; /** Contém o conteúdo do corpo da requisição. O padrão é um objeto vazio. */
    private string $port; /** Contém o host enviado pelo cabeçalho Host HTTP. */
    private string $ip; /** Contém o endereço ip remoto da requisição. */
    private string $method; /** tém uma string correspondente ao método HTTP da requisição: GET, POST, PUT, etc. */
    private array $params; /** Esta propriedade é um vetor contendo parâmetros de rota. */
    private string $protocol; /** tém o protocolo da requisição: http ou (requisições TLS) https. */
    private array $query; /** Esta propriedade é um objeto contendo uma propriedade para cada parâmetro de busca. */
    private string $route; /** tém a rota chamada na requisição. */

    public function __construct()
    {
        if (!isset($_POST, $_GET) || empty($_SERVER)) {
            throw new RuntimeException('Global variables are not defined.');
        }

        $php_input = file_get_contents('php://input');
        $request_url = filter_var((string) @$_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);

        try {
            $php_input_array = json_decode($php_input, true, 512, JSON_THROW_ON_ERROR);
            if (!is_array($php_input_array)) {
                $php_input_array = [];
            }
        } catch (JsonException $json_exception) {
            $php_input_array = [];
        }

        $this->setBody(array_merge($_POST, $php_input_array));
        $this->setPort((string) @$_SERVER['REMOTE_PORT']);
        $this->setIp((string) @$_SERVER['REMOTE_ADDR']);
        $this->setMethod((string) @$_SERVER['REQUEST_METHOD']);
        $this->setRoute((string) $request_url);
        $this->setParams(explode('/', explode('?', ltrim($request_url, '/'))[0]));
        $this->setProtocol((string) @$_SERVER['REQUEST_SCHEME']);
        $this->setQuery($_REQUEST);
    }

    public function getHeader(string $header): string
    {
        $headers = apache_request_headers();

        if (!$headers) {
            return '';
        }

        foreach ($headers as $header_key => $header_value) {
            if (strtolower($header_key) === strtolower($header)) {
                return $header_value;
            }
        }

        return '';
    }

    public function getBody(bool $sanitize = true): array
    {
        if (!$sanitize) {
            return $this->body;
        }

        $sanitized_body = [];

        foreach ($this->body as $key => $value) {
            $sanitized_body[$key] = filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);
        }

        return $sanitized_body;
    }

    public function setBody(array $body): void
    {
        $this->body = $body;
    }

    public function getPort(): string
    {
        return $this->port;
    }

    public function setPort(string $port): void
    {
        $this->port = $port;
    }

    public function getIp(): string
    {
        return $this->ip;
    }

    public function setIp(string $ip): void
    {
        $this->ip = $ip;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function setMethod(string $method): void
    {
        $this->method = $method;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function setParams(array $params): void
    {
        $this->params = $params;
    }

    public function getProtocol(): string
    {
        return $this->protocol;
    }

    public function setProtocol(string $protocol): void
    {
        $this->protocol = $protocol;
    }

    public function getQuery(): array
    {
        return $this->query;
    }

    public function setQuery(array $query): void
    {
        $this->query = $query;
    }

    public function getRoute(): string
    {
        return $this->route;
    }

    public function setRoute(string $route): void
    {
        $this->route = $route;
    }
}
