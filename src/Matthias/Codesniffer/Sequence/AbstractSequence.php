<?php

namespace Matthias\Codesniffer\Sequence;

use Matthias\Codesniffer\MatcherInterface;
use Matthias\Codesniffer\Sequence\Exception\EndOfSequence;
use Matthias\Codesniffer\Sequence\Exception\ExpectationNotMatched;
use Matthias\Codesniffer\Sequence\Expectation\ExpectationInterface;

abstract class AbstractSequence implements MatcherInterface, SequenceInterface
{
    /**
     * @var ExpectationInterface[]
     */
    private $expectations = array();

    protected $tokens;
    protected $currentIndex;

    abstract protected function getNextIndex();

    public function addExpectation(ExpectationInterface $expectation)
    {
        $this->expectations[] = $expectation;
    }

    public function matches(array $tokens, $tokenIndex)
    {
        $this->tokens = array_values($tokens);
        $this->currentIndex = $tokenIndex;

        try {
            foreach ($this->expectations as $expectation) {
                $expectation->match($this);
            }

            return true;
        } catch (ExpectationNotMatched $exception) {
            return false;
        }
    }

    public function endOfSequence()
    {
        return !isset($this->tokens[$this->getNextIndex()]);
    }

    public function peek()
    {
        $nextIndex = $this->getNextIndex();

        $this->validateIndex($nextIndex);

        return $this->tokens[$nextIndex];
    }

    public function current()
    {
        return $this->tokens[$this->currentIndex];
    }

    public function next()
    {
        $nextIndex = $this->getNextIndex();

        if (!isset($this->tokens[$nextIndex])) {
            throw new EndOfSequence();
        }

        $this->currentIndex = $nextIndex;

        return $this->tokens[$this->currentIndex];
    }

    private function validateIndex($index)
    {
        if (!isset($this->tokens[$index])) {
            throw new EndOfSequence();
        }
    }
}
