<?php

namespace Expr\Tests\Mocks;

use JsonException;

class Request
{
    /**
     * @return array
     * @throws
     */
    public function getPhpInputAsArray(): array
    {
        try {
            return json_decode(file_get_contents('php://input'), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            return [];
        }
    }
}