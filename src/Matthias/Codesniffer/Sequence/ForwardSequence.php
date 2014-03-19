<?php

namespace Matthias\Codesniffer\Sequence;

class ForwardSequence extends AbstractSequence
{
    protected function getNextIndex()
    {
        return $this->currentIndex + 1;
    }
}
