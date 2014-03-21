<?php

namespace Matthias\Codesniffer\Tests\Sequence;

use Matthias\Codesniffer\Sequence\BackwardSequence;
use Matthias\Codesniffer\Sequence\Expectation\Choice;
use Matthias\Codesniffer\Sequence\Expectation\ExactMatch;
use Matthias\Codesniffer\Sequence\Expectation\Quantity;
use Matthias\Codesniffer\Sequence\Expectation\Succeeding;
use Matthias\Codesniffer\Sequence\ForwardSequence;
use Matthias\Codesniffer\Sequence\SequenceBuilder;

class SequenceBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_creates_a_forward_sequence_with_exact_expectation()
    {
        $sequence = SequenceBuilder::create()
            ->lookingForward()
            ->expect()
                ->quantity()
                    ->exactly(2)
                    ->token(T_WHITESPACE, "\n")
                ->end()
            ->end()
            ->build();

        $expectedSequence = new ForwardSequence(array(
            new Quantity(new ExactMatch(T_WHITESPACE, "\n"), 2, 2)
        ));

        $this->assertEquals($expectedSequence, $sequence);
    }

    /**
     * @test
     */
    public function it_creates_a_backward_sequence_with_an_at_least_expectation()
    {
        $sequence = SequenceBuilder::create()
            ->lookingBackward()
            ->expect()
                ->quantity()
                    ->atLeast(3)
                    ->token(T_WHITESPACE, "\n")
                ->end()
            ->end()
            ->build();

        $expectedSequence = new BackwardSequence(array(
            new Quantity(new ExactMatch(T_WHITESPACE, "\n"), 3, null)
        ));

        $this->assertEquals($expectedSequence, $sequence);
    }

    /**
     * @test
     */
    public function it_creates_a_sequence_with_an_at_most_expectation()
    {
        $sequence = SequenceBuilder::create()
            ->lookingBackward()
            ->expect()
                ->quantity()
                    ->atMost(2)
                    ->token(T_WHITESPACE, "\n")
                ->end()
            ->end()
            ->build();

        $expectedSequence = new BackwardSequence(array(
            new Quantity(new ExactMatch(T_WHITESPACE, "\n"), null, 2)
        ));

        $this->assertEquals($expectedSequence, $sequence);
    }

    /**
     * @test
     */
    public function it_creates_a_sequence_with_a_range_expectation()
    {
        $sequence = SequenceBuilder::create()
            ->lookingBackward()
            ->expect()
                ->quantity()
                    ->atLeast(2)
                    ->atMost(4)
                    ->token(T_WHITESPACE, "\n")
                ->end()
            ->end()
            ->build();

        $expectedSequence = new BackwardSequence();
        $expectedSequence
            ->addExpectation(new Quantity(new ExactMatch(T_WHITESPACE, "\n"), 2, 4));

        $this->assertEquals($expectedSequence, $sequence);
    }

    /**
     * @test
     */
    public function it_creates_a_sequence_with_any_expectation()
    {
        $sequence = SequenceBuilder::create()
            ->expect()
                ->quantity()
                    ->any()
                    ->token(T_WHITESPACE, "\n")
                ->end()
            ->end()
            ->build();

        $expectedSequence = new ForwardSequence();
        $expectedSequence
            ->addExpectation(new Quantity(new ExactMatch(T_WHITESPACE, "\n"), null, null));

        $this->assertEquals($expectedSequence, $sequence);
    }

    /**
     * @test
     */
    public function it_creates_a_sequence_with_multiple_expectations()
    {
        $sequence = SequenceBuilder::create()
            ->expect()
                ->quantity()
                    ->any()
                    ->token(T_WHITESPACE, "\n")
                ->end()
                ->quantity()
                    ->exactly(2)
                    ->token(T_NAMESPACE)
                ->end()
            ->end()
            ->build();

        $expectedSequence = new ForwardSequence(array(
            new Quantity(new ExactMatch(T_WHITESPACE, "\n"), null, null),
            new Quantity(new ExactMatch(T_NAMESPACE), 2, 2)
        ));

        $this->assertEquals($expectedSequence, $sequence);
    }

    /**
     * @test
     */
    public function it_creates_a_sequence_with_succeeding_expectations()
    {
        $sequence = SequenceBuilder::create()
            ->expect()
                ->quantity()
                    ->atLeast(1)
                    ->atMost(2)
                    ->succeeding()
                        ->token(T_NS_SEPARATOR)
                        ->token(T_STRING)
                    ->end()
                ->end()
            ->end()
            ->build();

        $expectedSequence = new ForwardSequence(array(
            new Quantity(
                new Succeeding(
                    array(
                        new ExactMatch(T_NS_SEPARATOR),
                        new ExactMatch(T_STRING)
                    )
                ),
                1,
                2
            )
        ));

        $this->assertEquals($expectedSequence, $sequence);
    }

    /**
     * @test
     */
    public function it_creates_a_sequence_with_choice_expectations()
    {
        $sequence = SequenceBuilder::create()
            ->expect()
                ->quantity()
                    ->choice()
                        ->token(T_NS_SEPARATOR)
                        ->token(T_STRING)
                    ->end()
                ->end()
            ->end()
            ->build();

        $expectedSequence = new ForwardSequence(array(
            new Quantity(
                new Choice(
                    array(
                        new ExactMatch(T_NS_SEPARATOR),
                        new ExactMatch(T_STRING)
                    )
                ),
                null,
                null
            )
        ));

        $this->assertEquals($expectedSequence, $sequence);
    }

    /**
     * @test
     */
    public function it_fails_when_no_expectation_have_been_defined()
    {
        $this->setExpectedException('\LogicException');

        SequenceBuilder::create()->build();
    }
}
