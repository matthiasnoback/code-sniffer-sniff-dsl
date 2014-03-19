<?php

namespace Matthias\Codesniffer\Sequence;

use Matthias\Codesniffer\Sequence\Exception\EndOfSequence;

interface SequenceInterface
{
    /**
     * Move the pointer
     *
     * @throws EndOfSequence
     * @return array The next token array
     */
    public function next();

    /**
     * @return array The current token array
     */
    public function current();

    /**
     * @return boolean Whether or not the end of the sequence has been reached
     */
    public function endOfSequence();

    /**
     * Don't touch the pointer
     *
     * @return array The next token array
     */
    public function peek();
}
