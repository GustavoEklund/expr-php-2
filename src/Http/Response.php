<?php

namespace Expr\Http;

use JsonException;

/**
 * Class Response
 * @package Expr
 */
class Response
{
    private array $enveloped_data = [];
    private string $body = '';

    /**
     * Retorna a resposta em formato JSON a partir de um array.
     *
     * @param array $value
     * @return Response
     * @throws JsonException
     */
    public function json(array $value): Response
    {
        $this->setHeader('Content-Type', 'application/json');
        $this->body = json_encode(array_merge($value, $this->enveloped_data), JSON_THROW_ON_ERROR);
        return $this;
    }

    /**
     * Retorna a resposta em formato de texto plano.
     *
     * @param string $value
     * @return Response
     */
    public function send($value = ''): Response
    {
        $this->setHeader('Content-Type', 'text/plain');
        $this->body = $value;
        return $this;
    }

    /**
     * Define o código de status HTTP antes do retorno.
     *
     * @param int $code
     * @return $this
     */
    public function status(int $code): Response
    {
        http_response_code($code);
        return $this;
    }

    /**
     * Add key-value pair to json response body.
     *
     * @param string $key
     * @param $value
     * @return Response
     */
    public function append(string $key, $value): Response
    {
        $this->enveloped_data[$key] = $value;
        return $this;
    }

    /**
     * Set an http header value.
     *
     * @param string $header_name
     * @param string $value
     * @return Response
     */
    private function setHeader(string $header_name, string $value): Response
    {
        @header("{$header_name}: {$value}");
        return $this;
    }

    public function getBody(): string
    {
        return $this->body;
    }
}
