<?php

namespace Matthias\Codesniffer\Sequence\Expectation;

use Matthias\Codesniffer\Sequence\Exception\ExpectationNotMatched;
use Matthias\Codesniffer\Sequence\SequenceInterface;

class Quantity implements ExpectationInterface
{
    const ANY = null;

    private $innerExpectation;
    private $minimum;
    private $maximum;

    public function __construct(ExpectationInterface $innerExpectation, $minimum, $maximum)
    {
        $this->innerExpectation = $innerExpectation;
        $this->minimum = $minimum;
        $this->maximum = $maximum;
    }

    public function match(SequenceInterface $sequence)
    {
        $occurrences = 0;

        while (!$sequence->endOfSequence()) {
            try {
                $this->innerExpectation->match($sequence);
                $occurrences++;
                $sequence->next();
            } catch (ExpectationNotMatched $exception) {
                break;
            }
        }

        $this->matchOccurrences($occurrences);
    }

    private function matchOccurrences($occurrences)
    {
        if ($this->minimum !== null && $occurrences < $this->minimum) {
            throw new ExpectationNotMatched();
        }

        if ($this->maximum !== null && $occurrences > $this->maximum) {
            throw new ExpectationNotMatched();
        }
    }
}
