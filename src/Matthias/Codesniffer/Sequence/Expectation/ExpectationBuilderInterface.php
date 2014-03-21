<?php

namespace Matthias\Codesniffer\Sequence\Expectation;

interface ExpectationBuilderInterface
{
    /**
     * @return ExpectationBuilderInterface[]
     */
    public function getExpectations();
}
