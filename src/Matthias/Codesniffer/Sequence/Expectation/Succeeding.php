<?php

namespace Matthias\Codesniffer\Sequence\Expectation;

use Assert\Assertion;
use Matthias\Codesniffer\Sequence\SequenceInterface;

class Succeeding implements ExpectationInterface
{
    /**
     * @var ExpectationInterface[]
     */
    private $expectations;

    public function __construct(array $expectations)
    {
        $this->expectations = $expectations;
        Assertion::allIsInstanceOf(
            $expectations,
            'Matthias\Codesniffer\Sequence\Expectation\ExpectationInterface',
            null,
            null
        );
    }

    public function match(SequenceInterface $sequence)
    {
        foreach ($this->expectations as $expectation) {
            $expectation->match($sequence);
            //$sequence->next();
        }
    }
}
