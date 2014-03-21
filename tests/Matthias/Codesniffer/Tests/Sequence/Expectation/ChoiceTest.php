<?php

namespace Matthias\Codesniffer\Tests\Sequence\Expectation;

use Matthias\Codesniffer\Sequence\Expectation\Choice;
use Matthias\Codesniffer\Sequence\Expectation\ExactMatch;
use Matthias\Codesniffer\Sequence\ForwardSequence;
use Matthias\Codesniffer\TokenBuilder;

class ChoiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider tokensProvider
     */
    public function it_matches_succeeding_tokens_optionally(
        array $expectations,
        array $tokens,
        $tokenIndex,
        $expectedToMatch
    ) {
        $sequence = new ForwardSequence(array(new Choice($expectations)));

        $this->assertSame($expectedToMatch, $sequence->matches($tokens, $tokenIndex));
    }

    public function tokensProvider()
    {
        return array(
            array(
                // no matches at all
                array(
                    new ExactMatch(T_NAMESPACE),
                    new ExactMatch(T_CLASS)
                ),
                array(
                    TokenBuilder::create('T_WHITESPACE')->build(),
                    TokenBuilder::create('T_WHITESPACE')->build(),
                ),
                0,
                false
            ),
            array(
                // one of them matches
                array(
                    new ExactMatch(T_NAMESPACE),
                    new ExactMatch(T_CLASS)
                ),
                array(
                    TokenBuilder::create('T_WHITESPACE')->build(),
                    TokenBuilder::create('T_NAMESPACE')->build(),
                ),
                0,
                true
            ),
            array(
                // the other one matches
                array(
                    new ExactMatch(T_NAMESPACE),
                    new ExactMatch(T_CLASS)
                ),
                array(
                    TokenBuilder::create('T_WHITESPACE')->build(),
                    TokenBuilder::create('T_CLASS')->build(),
                ),
                0,
                true
            )
        );
    }
}
