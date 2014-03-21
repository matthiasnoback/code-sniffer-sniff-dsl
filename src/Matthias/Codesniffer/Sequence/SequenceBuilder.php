<?php

namespace Matthias\Codesniffer\Sequence;

use Matthias\Codesniffer\Sequence\Expectation\ExpectationBuilderInterface;
use Matthias\Codesniffer\Sequence\Expectation\RootExpectationBuilder;

class SequenceBuilder
{
    private $lookingForward = true;

    /**
     * @var ExpectationBuilderInterface|null
     */
    private $expectationBuilder;

    public static function create()
    {
        return new static();
    }

    public function lookingForward()
    {
        $this->lookingForward = true;

        return $this;
    }

    public function lookingBackward()
    {
        $this->lookingForward = false;

        return $this;
    }

    public function expect()
    {
        $this->expectationBuilder = new RootExpectationBuilder($this);

        return $this->expectationBuilder;
    }

    public function build()
    {
        $sequence = $this->createSequence();

        if (!($this->expectationBuilder instanceof ExpectationBuilderInterface)) {
            throw new \LogicException('You forgot to call expect()');
        }

        foreach ($this->expectationBuilder->getExpectations() as $expectation) {
            $sequence->addExpectation($expectation);
        }

        return $sequence;
    }

    /**
     * @return AbstractSequence
     */
    private function createSequence()
    {
        $class = $this->lookingForward ? 'ForwardSequence' : 'BackwardSequence';

        $class = 'Matthias\Codesniffer\Sequence\\'.$class;

        return new $class();
    }
}
