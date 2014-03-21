<?php

namespace Matthias\Codesniffer\Sequence\Expectation;

class QuantityBuilder extends AbstractConcreteExpectationBuilder
{
    private $minimum;
    private $maximum;

    protected function buildExpectation()
    {
        return new Quantity(reset($this->expectations), $this->minimum, $this->maximum);
    }

    public function exactly($quantity)
    {
        $this->minimum = $quantity;
        $this->maximum = $quantity;

        return $this;
    }

    public function atLeast($minimum)
    {
        $this->minimum = $minimum;

        return $this;
    }

    public function atMost($maximum)
    {
        $this->maximum = $maximum;

        return $this;
    }

    public function any()
    {
        $this->minimum = null;
        $this->maximum = null;

        return $this;
    }
}
