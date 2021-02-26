<?php

namespace Expr\Helpers;

use JsonException;

final class Stream
{
    public static function getPhpInput(): string
    {
        return file_get_contents('php://input') ?? '';
    }

    public static function getPhpInputAsArray(): array
    {
        try {
            $php_input_array = json_decode(self::getPhpInput(), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $json_exception) {
            return [];
        }
        if (!is_array($php_input_array)) {
            return [];
        }
        return $php_input_array;
    }
}