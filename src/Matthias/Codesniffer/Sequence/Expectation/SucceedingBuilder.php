<?php

namespace Matthias\Codesniffer\Sequence\Expectation;

class SucceedingBuilder extends AbstractConcreteExpectationBuilder
{
    protected function buildExpectation()
    {
        return new Succeeding($this->expectations);
    }
}
