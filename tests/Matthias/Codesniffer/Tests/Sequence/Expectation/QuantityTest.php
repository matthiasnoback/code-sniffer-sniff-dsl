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
    public function test_matcher(ExpectationInterface $innerExpectation, $minimum, $maximum, array $tokens, $tokenIndex, $expectedToMatch)
    {
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
                    TokenBuilder::createOpenTag()->build(),
                    TokenBuilder::createNewLine()->build(),
                    TokenBuilder::createNewLine()->build()
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
                    TokenBuilder::createOpenTag()->build(),
                    TokenBuilder::create('T_NAMESPACE')->build(),
                    TokenBuilder::createNewLine()->build(),
                    TokenBuilder::createClass()->build(),
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
                    TokenBuilder::createOpenTag()->build(),
                    TokenBuilder::createNewLine()->build(),
                    TokenBuilder::createNewLine()->build(),
                    TokenBuilder::createNewLine()->build()
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
                    TokenBuilder::createOpenTag()->build(),
                    TokenBuilder::createClass()->build()
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
                    TokenBuilder::createOpenTag()->build(),
                    TokenBuilder::createNewLine()->build(),
                    TokenBuilder::createNewLine()->build(),
                    TokenBuilder::createNewLine()->build(),
                    TokenBuilder::createNewLine()->build()
                ),
                0,
                true
            ),
        );
    }
}
