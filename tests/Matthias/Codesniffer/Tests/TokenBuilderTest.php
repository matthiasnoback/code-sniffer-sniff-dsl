<?php

namespace Matthias\Codesniffer\Tests\Sequence;

use Matthias\Codesniffer\TokenBuilder;

class TokenBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_calculates_the_code_for_a_type_provided_as_string()
    {
        $token = TokenBuilder::create('T_NAMESPACE')->build();
        $this->tokenHasCode(T_NAMESPACE, $token);
    }

    /**
     * @test
     */
    public function it_fails_if_type_is_not_a_string()
    {
        $this->setExpectedException('\InvalidArgumentException');
        TokenBuilder::create(T_NAMESPACE);
    }

    /**
     * @test
     */
    public function it_fails_if_type_does_not_start_with_t_underscore()
    {
        $this->setExpectedException('\InvalidArgumentException');

        TokenBuilder::create('PATHINFO_EXTENSION');
    }

    /**
     * @test
     */
    public function it_fails_if_type_constant_is_not_defined()
    {
        $this->setExpectedException('\InvalidArgumentException');

        TokenBuilder::create('T_UNDEFINED_CONSTANT');
    }

    private function tokenHasCode($expectedCode, array $token)
    {
        $this->assertSame($expectedCode, $token['code']);
    }
}
