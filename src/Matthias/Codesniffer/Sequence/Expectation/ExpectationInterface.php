<?php

namespace Matthias\Codesniffer\Sequence\Expectation;

use Matthias\Codesniffer\Sequence\SequenceInterface;

interface ExpectationInterface
{
    public function match(SequenceInterface $sequence);
}
