<?php

namespace Matthias\Codesniffer\Tests\Sequence\Expectation;

use Matthias\Codesniffer\Sequence\Expectation\ExactMatch;
use Matthias\Codesniffer\Sequence\ForwardSequence;
use Matthias\Codesniffer\TokenBuilder;

class ExactMatchTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider tokensProvider
     */
    public function it_matches_exactly_one_token($tokens, $tokenIndex, $expectedToMatch)
    {
        $sequence = new ForwardSequence();
        $sequence->addExpectation(new ExactMatch(T_WHITESPACE));

        $this->assertSame($expectedToMatch, $sequence->matches($tokens, $tokenIndex));
    }

    public function tokensProvider()
    {
        return array(
            // next token is indeed a new line
            array(
                array(
                    TokenBuilder::create('T_OPEN_TAG')->build(),
                    TokenBuilder::create('T_WHITESPACE')->build()
                ),
                0,
                true
            ),
            // next token is not a new line
            array(
                array(
                    TokenBuilder::create('T_OPEN_TAG')->build(),
                    TokenBuilder::create('T_CLASS')->build()
                ),
                0,
                false
            ),
            // next token does not exist
            array(
                array(
                    TokenBuilder::create('T_OPEN_TAG')->build()
                ),
                0,
                false
            ),
        );
    }
}
