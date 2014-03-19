<?php

namespace Matthias\Codesniffer\Sequence\Expectation;

use Matthias\Codesniffer\Sequence\Exception\ExpectationNotMatched;
use Matthias\Codesniffer\Sequence\SequenceInterface;

class ExactMatch implements ExpectationInterface
{
    private $code;
    private $content;

    public function __construct($code, $content = null)
    {
        $this->code = $code;
        $this->content = $content;
    }

    public static function create($code, $content = null)
    {
        return new static($code, $content);
    }

    public function match(SequenceInterface $sequence)
    {
        if ($sequence->endOfSequence()) {
            throw new ExpectationNotMatched();
        }

        $nextToken = $sequence->peek();

        if ($nextToken['code'] === $this->code) {
            if ($this->content === null || $this->content === $nextToken['content']) {
                return;
            }
        }

        throw new ExpectationNotMatched();
    }
}
