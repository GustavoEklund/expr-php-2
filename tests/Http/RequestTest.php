<?php

namespace Expr\Tests\Http;

use Expr\Http\Request;
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

    public function test_assert_areGlobalsDefined_returns_true_if_required_globals_are_defined(): void
    {
        self::assertTrue($this->sut->areGlobalsDefined());
    }

    public function test_assert_getBody_return_sub_keys_correctly(): void
    {
        $_POST = [
            'any_key' => 'any_value',
            'any_compound_key' => [
                'any_sub_key' => 'any_sub_value',
                'any_compound_key_2' => [
                    'other_key' => 'other_value'
                ],
                'any_sub_key2' => 'any_sub_value2'
            ],
            'any_key_2' => 'any_value2'
        ];

        $this->sut = new Request();
        $body = $this->sut->getBody();

        self::assertEquals($_POST, $body);
    }
}
