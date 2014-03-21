<?php

namespace Matthias\Codesniffer\Sequence\Expectation;

class BaseExpectationBuilder implements ExpectationBuilderInterface
{
    protected $expectations = array();

    public function token($code, $content = null)
    {
        $this->expectations[] = new ExactMatch($code, $content);

        return $this;
    }

    protected function addExpectation(ExpectationInterface $expectation)
    {
        $this->expectations[] = $expectation;
    }

    public function getExpectations()
    {
        return $this->expectations;
    }

    public function succeeding()
    {
        return new SucceedingBuilder($this);
    }

    public function choice()
    {
        return new ChoiceBuilder($this);
    }

    public function quantity()
    {
        return new QuantityBuilder($this);
    }
}
