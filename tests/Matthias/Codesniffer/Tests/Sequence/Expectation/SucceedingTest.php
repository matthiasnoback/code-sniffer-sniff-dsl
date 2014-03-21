<?php

namespace Matthias\Codesniffer\Tests\Sequence\Expectation;

use Matthias\Codesniffer\Sequence\Expectation\ExactMatch;
use Matthias\Codesniffer\Sequence\Expectation\Succeeding;
use Matthias\Codesniffer\Sequence\ForwardSequence;
use Matthias\Codesniffer\TokenBuilder;

class SucceedingTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider tokensProvider
     */
    public function it_matches_succeeding_tokens(array $expectations, array $tokens, $tokenIndex, $expectedToMatch)
    {
        $sequence = new ForwardSequence(array(new Succeeding($expectations)));

        $this->assertSame($expectedToMatch, $sequence->matches($tokens, $tokenIndex));
    }

    public function tokensProvider()
    {
        return array(
            array(
                // first token matches, second does not
                array(
                    new ExactMatch(T_NAMESPACE),
                    new ExactMatch(T_CLASS)
                ),
                array(
                    TokenBuilder::create('T_WHITESPACE')->build(),
                    TokenBuilder::create('T_NAMESPACE')->build(),
                    TokenBuilder::create('T_WHITESPACE')->build(),
                ),
                0,
                false
            ),
            array(
                // exact match
                array(
                    new ExactMatch(T_NAMESPACE),
                    new ExactMatch(T_CLASS)
                ),
                array(
                    TokenBuilder::create('T_WHITESPACE')->build(),
                    TokenBuilder::create('T_NAMESPACE')->build(),
                    TokenBuilder::create('T_CLASS')->build(),
                ),
                0,
                true
            )
        );
    }
}
