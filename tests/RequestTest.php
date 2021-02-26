<?php

namespace Expr {
    function file_get_contents(): string {
        return '{"any_key":"any_value"}';
    }
}

namespace Expr\Tests\Mocks {
    function file_get_contents(): string {
        return 'invalid_input';
    }
}

namespace Expr\Tests {
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

        public function test_assert_areGlobalsDefined_returns_true_if_required_globals_are_defined(): void
        {
            self::assertTrue($this->sut->areGlobalsDefined());
        }

        public function test_assert_getPhpInputAsArray_invalid_input_returns_empty_array(): void
        {
            $sut = new Mocks\Request();
            self::assertEmpty($sut->getPhpInputAsArray());
        }
    }
}
