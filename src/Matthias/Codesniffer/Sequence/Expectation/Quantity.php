<?php

namespace Matthias\Codesniffer\Sequence\Expectation;

use Matthias\Codesniffer\Sequence\Exception\ExpectationNotMatched;
use Matthias\Codesniffer\Sequence\SequenceInterface;

class Quantity implements ExpectationInterface
{
    const ANY = null;

    /**
     * @var $innerExpectation ExpectationInterface[]
     */
    private $innerExpectations;
    private $minimum;
    private $maximum;

    public function __construct($innerExpectation, $minimum, $maximum)
    {
        // TODO this should be refactored, probably introduce an "iterator" expectation
        $this->innerExpectations = is_array($innerExpectation) ? $innerExpectation : array($innerExpectation);
        $this->minimum = $minimum;
        $this->maximum = $maximum;
    }

    public function match(SequenceInterface $sequence)
    {
        $occurrences = 0;

        while (!$sequence->endOfSequence()) {
            try {
                foreach ($this->innerExpectations as $innerExpectation) {
                    $innerExpectation->match($sequence);
                    $sequence->next();
                }

                $occurrences++;
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
