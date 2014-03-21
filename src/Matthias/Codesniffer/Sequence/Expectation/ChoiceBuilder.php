<?php

namespace Matthias\Codesniffer\Sequence\Expectation;

class ChoiceBuilder extends AbstractConcreteExpectationBuilder
{
    protected function buildExpectation()
    {
        return new Choice($this->expectations);
    }
}
