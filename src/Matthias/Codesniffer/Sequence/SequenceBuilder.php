<?php

namespace Matthias\Codesniffer\Sequence;

use Matthias\Codesniffer\Sequence\Expectation\ExactMatch;
use Matthias\Codesniffer\Sequence\Expectation\Quantity;

class SequenceBuilder
{
    private $minimum;
    private $maximum;
    private $innerExpectation;
    private $lookingForward = true;
    private $expectations = array();

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
        return $this;
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

    public function tokens($code, $content = null)
    {
        $this->innerExpectation = new ExactMatch($code, $content);

        return $this;
    }

    public function token($code, $content = null)
    {
        return $this->tokens($code, $content);
    }

    public function then()
    {
        $this->finishExpectation();

        return $this;
    }

    public function build()
    {
        $this->finishExpectation();

        $sequence = $this->createSequence();

        foreach ($this->expectations as $expectation) {
            $sequence->addExpectation($expectation);
        }

        return $sequence;
    }

    /**
     * @return AbstractSequence
     */
    private function createSequence()
    {
        $class = $this->lookingForward ? 'Matthias\Codesniffer\Sequence\ForwardSequence' : 'Matthias\Codesniffer\Sequence\BackwardSequence';

        return new $class();
    }

    private function finishExpectation()
    {
        if ($this->innerExpectation === null) {
            throw new \LogicException('You forgot to call exactly(), atLeat(), atMost(), or any()');
        }

        $this->expectations[] = new Quantity($this->innerExpectation, $this->minimum, $this->maximum);

        $this->innerExpectation = null;
        $this->minimum = null;
        $this->maximum = null;
    }
}
