<?php

namespace Matthias\Codesniffer\Tests\Sequence;

use Matthias\Codesniffer\Sequence\Expectation\ExactMatch;
use Matthias\Codesniffer\Sequence\Expectation\Quantity;
use Matthias\Codesniffer\Sequence\Expectation\Succeeding;
use Matthias\Codesniffer\Sequence\ForwardSequence;
use Matthias\Codesniffer\TokenBuilder;

class IntegrationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider tokensProvider
     */
    public function it_matches_a_complicated_set_of_expectations(
        array $expectations,
        array $tokens,
        $expectedToMatch
    ) {
        $sequence = new ForwardSequence($expectations);

        $this->assertSame($expectedToMatch, $sequence->matches($tokens, 0));
    }

    public function tokensProvider()
    {
        return array(
            // pattern for namespace declarations
            array(
                array(
                    new Succeeding(
                        array(
                            new ExactMatch(T_WHITESPACE),
                            new ExactMatch(T_NAMESPACE),
                            new ExactMatch(T_WHITESPACE),
                            new ExactMatch(T_STRING),
                        )
                    ),
                    new Quantity(
                        new Succeeding(array(
                            new ExactMatch(T_NS_SEPARATOR),
                            new ExactMatch(T_STRING),
                        )),
                        null,
                        null
                    ),
                    new ExactMatch(T_SEMICOLON)
                ),
                array(
                    TokenBuilder::create('T_OPEN_TAG')->build(),
                    TokenBuilder::create('T_WHITESPACE')->build(),
                    TokenBuilder::create('T_NAMESPACE')->build(),
                    TokenBuilder::create('T_WHITESPACE')->build(),
                    TokenBuilder::create('T_STRING')->build(),
                    TokenBuilder::create('T_NS_SEPARATOR')->build(),
                    TokenBuilder::create('T_STRING')->build(),
                    TokenBuilder::create('T_NS_SEPARATOR')->build(),
                    TokenBuilder::create('T_STRING')->build(),
                    TokenBuilder::create('T_SEMICOLON')->build()
                ),
                true
            )
        );
    }
}
