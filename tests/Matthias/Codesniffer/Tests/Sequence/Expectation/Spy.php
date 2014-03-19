<?php

namespace Matthias\Codesniffer\Tests\Sequence\Expectation;

use Matthias\Codesniffer\Sequence\Expectation\ExpectationInterface;
use Matthias\Codesniffer\Sequence\SequenceInterface;

class Spy implements ExpectationInterface
{
    private $tokens = array();

    public function match(SequenceInterface $sequence)
    {
        while (!$sequence->endOfSequence()) {
            $sequence->next();
            $this->tokens[] = $sequence->current();
        }
    }

    public function getTokens()
    {
        return $this->tokens;
    }
}
