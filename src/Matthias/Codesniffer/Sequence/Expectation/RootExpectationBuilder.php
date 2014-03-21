<?php

namespace Matthias\Codesniffer\Sequence\Expectation;

use Matthias\Codesniffer\Sequence\SequenceBuilder;

class RootExpectationBuilder extends BaseExpectationBuilder
{
    private $sequenceBuilder;

    public function __construct(SequenceBuilder $sequenceBuilder)
    {
        $this->sequenceBuilder = $sequenceBuilder;
    }

    public function end()
    {
        return $this->sequenceBuilder;
    }
}
