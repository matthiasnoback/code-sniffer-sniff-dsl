<?php

namespace Matthias\Codesniffer\Sequence\Expectation;

abstract class AbstractConcreteExpectationBuilder extends BaseExpectationBuilder
{
    protected $parentBuilder;

    public function __construct(BaseExpectationBuilder $parentBuilder)
    {
        $this->parentBuilder = $parentBuilder;
    }

    /**
     * @return ExpectationInterface
     */
    abstract protected function buildExpectation();

    public function end()
    {
        $this->parentBuilder->addExpectation($this->buildExpectation());

        return $this->parentBuilder;
    }
}
