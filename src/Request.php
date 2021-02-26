<?php

namespace Expr;

use Expr\Helpers\Stream;
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
        if (!$this->areGlobalsDefined()) {
            throw new RuntimeException('Global variables are not defined.');
        }

        $request_url = $this->getRequestUrl();

        $this->body = array_merge($_POST, Stream::getPhpInputAsArray());
        $this->port = (string) @$_SERVER['REMOTE_PORT'];
        $this->ip = (string) @$_SERVER['REMOTE_ADDR'];
        $this->method = (string) @$_SERVER['REQUEST_METHOD'];
        $this->route = $request_url;
        $this->params = explode('/', explode('?', ltrim($request_url, '/'))[0]);
        $this->protocol = (string) @$_SERVER['REQUEST_SCHEME'];
        $this->query = $_REQUEST;
    }

    public function areGlobalsDefined(): bool
    {
        return isset($_POST, $_GET) && !empty($_SERVER);
    }

    public function getRequestUrl(): string
    {
        return (string) filter_var((string) @$_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);
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

    public function getPort(): string
    {
        return $this->port;
    }

    public function getIp(): string
    {
        return $this->ip;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function getProtocol(): string
    {
        return $this->protocol;
    }

    public function getQuery(): array
    {
        return $this->query;
    }

    public function getRoute(): string
    {
        return $this->route;
    }
}
