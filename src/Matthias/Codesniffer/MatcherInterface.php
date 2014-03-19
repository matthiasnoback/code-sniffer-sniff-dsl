<?php

namespace Matthias\Codesniffer;

interface MatcherInterface
{
    public function matches(array $tokens, $tokenIndex);
}
