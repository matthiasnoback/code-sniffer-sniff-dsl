<?php

namespace Matthias\Codesniffer\Sequence;

class BackwardSequence extends AbstractSequence
{
    protected function getNextIndex()
    {
        return $this->currentIndex - 1;
    }
}
