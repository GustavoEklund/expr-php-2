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
        $this->sut = new Request();
    }

    public function test_assert_areGlobalsDefined_returns_false_if_POST_is_not_defined(): void
    {
        unset($_POST);
        self::assertFalse($this->sut->areGlobalsDefined());
    }
}
