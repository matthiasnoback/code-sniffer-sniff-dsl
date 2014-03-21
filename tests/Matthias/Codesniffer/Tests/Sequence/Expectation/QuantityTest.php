<?php

namespace Matthias\Codesniffer\Tests\Sequence\Expectation;

use Matthias\Codesniffer\Sequence\Expectation\ExactMatch;
use Matthias\Codesniffer\Sequence\Expectation\ExpectationInterface;
use Matthias\Codesniffer\Sequence\Expectation\Quantity;
use Matthias\Codesniffer\Sequence\ForwardSequence;
use Matthias\Codesniffer\TokenBuilder;

class QuantityTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider tokensProvider
     */
    public function it_matches_a_number_of_tokens(
        ExpectationInterface $innerExpectation,
        $minimum,
        $maximum,
        array $tokens,
        $tokenIndex,
        $expectedToMatch
    ) {
        $sequence = new ForwardSequence();
        $sequence->addExpectation(new Quantity($innerExpectation, $minimum, $maximum));

        $this->assertSame($expectedToMatch, $sequence->matches($tokens, $tokenIndex));
    }

    public function tokensProvider()
    {
        return array(
            // expect exactly two new lines
            array(
                new ExactMatch(T_WHITESPACE, "\n"),
                2,
                2,
                array(
                    TokenBuilder::create('T_OPEN_TAG')->build(),
                    TokenBuilder::create('T_WHITESPACE')->setContent("\n")->build(),
                    TokenBuilder::create('T_WHITESPACE')->setContent("\n")->build()
                ),
                0,
                true
            ),
            // expect at least 1, at most 2 new lines
            array(
                new \Matthias\Codesniffer\Sequence\Expectation\ExactMatch(T_WHITESPACE, "\n"),
                1,
                2,
                array(
                    TokenBuilder::create('T_OPEN_TAG')->build(),
                    TokenBuilder::create('T_NAMESPACE')->build(),
                    TokenBuilder::create('T_WHITESPACE')->setContent("\n")->build(),
                    TokenBuilder::create('T_CLASS')->build(),
                ),
                1,
                true
            ),
            // expect at most two new lines
            array(
                new \Matthias\Codesniffer\Sequence\Expectation\ExactMatch(T_WHITESPACE, "\n"),
                2,
                2,
                array(
                    TokenBuilder::create('T_OPEN_TAG')->build(),
                    TokenBuilder::create('T_WHITESPACE')->setContent("\n")->build(),
                    TokenBuilder::create('T_WHITESPACE')->setContent("\n")->build(),
                    TokenBuilder::create('T_WHITESPACE')->setContent("\n")->build(),
                ),
                0,
                false
            ),
            // expect at most two new lines, but no minimum
            array(
                new ExactMatch(T_WHITESPACE, "\n"),
                null,
                2,
                array(
                    TokenBuilder::create('T_OPEN_TAG')->build(),
                    TokenBuilder::create('T_CLASS')->build(),
                ),
                0,
                true
            ),
            // expect at least two new lines, but no minimum
            array(
                new \Matthias\Codesniffer\Sequence\Expectation\ExactMatch(T_WHITESPACE, "\n"),
                2,
                null,
                array(
                    TokenBuilder::create('T_OPEN_TAG')->build(),
                    TokenBuilder::create('T_WHITESPACE')->setContent("\n")->build(),
                    TokenBuilder::create('T_WHITESPACE')->setContent("\n")->build(),
                    TokenBuilder::create('T_WHITESPACE')->setContent("\n")->build(),
                    TokenBuilder::create('T_WHITESPACE')->setContent("\n")->build(),
                ),
                0,
                true
            ),
        );
    }
}
