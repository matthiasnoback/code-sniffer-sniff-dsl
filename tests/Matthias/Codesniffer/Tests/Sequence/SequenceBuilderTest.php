<?php

namespace Matthias\Codesniffer\Tests\Sequence;

use Matthias\Codesniffer\Sequence\BackwardSequence;
use Matthias\Codesniffer\Sequence\Expectation\ExactMatch;
use Matthias\Codesniffer\Sequence\Expectation\Quantity;
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
            ->exactly(2)
            ->tokens(T_WHITESPACE, "\n")
            ->build();

        $expectedSequence = new ForwardSequence();
        $expectedSequence
            ->addExpectation(new Quantity(new ExactMatch(T_WHITESPACE, "\n"), 2, 2));

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
            ->atLeast(3)
            ->tokens(T_WHITESPACE, "\n")
            ->build();

        $expectedSequence = new BackwardSequence();
        $expectedSequence
            ->addExpectation(new Quantity(new ExactMatch(T_WHITESPACE, "\n"), 3, null));

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
            ->atMost(2)
            ->tokens(T_WHITESPACE, "\n")
            ->build();

        $expectedSequence = new BackwardSequence();
        $expectedSequence
            ->addExpectation(new Quantity(new ExactMatch(T_WHITESPACE, "\n"), null, 2));

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
            ->atLeast(2)
            ->atMost(4)
            ->tokens(T_WHITESPACE, "\n")
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
            ->any()
            ->token(T_WHITESPACE, "\n")
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
            ->any()
            ->token(T_WHITESPACE, "\n")
            ->then()
            ->exactly(2)
            ->tokens(T_NAMESPACE)
            ->build();

        $expectedSequence = new ForwardSequence();
        $expectedSequence->addExpectation(new Quantity(new ExactMatch(T_WHITESPACE, "\n"), null, null));
        $expectedSequence->addExpectation(new Quantity(new ExactMatch(T_NAMESPACE), 2, 2));

        $this->assertEquals($expectedSequence, $sequence);
    }

    /**
     * @test
     */
    public function it_fails_when_no_expectation_has_been_defined()
    {
        $this->setExpectedException('\LogicException');

        SequenceBuilder::create()->build();
    }
}
