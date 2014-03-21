<?php

namespace Matthias\Codesniffer\Tests\Sequence;

use Matthias\Codesniffer\TokenBuilder;

class TokenBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_calculates_the_type_for_a_code()
    {
        $token = TokenBuilder::create('T_NAMESPACE')->build();
        $this->tokenHasCode(T_NAMESPACE, $token);
        $this->tokenHasType('T_NAMESPACE', $token);
    }

    /**
     * @test
     */
    public function it_fails_when_token_code_is_not_an_integer()
    {
        $this->setExpectedException('\InvalidArgumentException');
        TokenBuilder::create('not an integer')->build();
    }

    /**
     * @test
     */
    public function it_fails_when_token_code_is_unknown()
    {
        $this->setExpectedException('\InvalidArgumentException');

        TokenBuilder::create(12345)->build();
    }

    private function tokenHasCode($expectedCode, array $token)
    {
        $this->assertSame($expectedCode, $token['code']);
    }

    private function tokenHasType($expectedType, array $token)
    {
        $this->assertSame($expectedType, $token['type']);
    }
}
