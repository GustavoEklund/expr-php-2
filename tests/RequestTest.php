<?php

namespace Expr\Tests;

use Expr\Request;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    private Request $sut;

    public function setUp(): void
    {
        $_POST = ['any_key' => 'any_value'];
        $_GET = ['any_key' => 'any_value'];
        $_SERVER = ['any_key' => 'any_value'];
        $this->sut = new Request();
    }

    public function test_assert_areGlobalsDefined_returns_false_if_POST_is_not_defined(): void
    {
        unset($_POST);
        self::assertFalse($this->sut->areGlobalsDefined());
    }

    public function test_assert_areGlobalsDefined_returns_false_if_GET_is_not_defined(): void
    {
        unset($_GET);
        self::assertFalse($this->sut->areGlobalsDefined());
    }

    public function test_assert_areGlobalsDefined_returns_false_if_SERVER_is_empty(): void
    {
        $_SERVER = [];
        self::assertFalse($this->sut->areGlobalsDefined());
    }
}
