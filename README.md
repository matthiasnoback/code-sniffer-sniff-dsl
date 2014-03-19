# DSL for writing PHP_CodeSniffer sniffs

[![Build Status](https://secure.travis-ci.org/matthiasnoback/code-sniffer-sniff-dsl.png)](http://travis-ci.org/matthiasnoback/code-sniffer-sniff-dsl)

Using this library you can easily write code sniffs for [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer).
When you don't use this DSL you will find yourself writing lots of specific, never reusable code.

## Example

Say you want to create a code sniff that triggers an error if a PHP file contains an (auto-generated) doc comment at the
top (this is called a "file comment"). You should first create a "matcher" for this specific situation:

```php
<?php

namespace Matthias\PhpCodingStandard\Sniffs\Commenting;

use Matthias\Codesniffer\MatcherInterface;
use Matthias\Codesniffer\Sequence\SequenceBuilder;

class FileCommentMatcher implements MatcherInterface
{
    public function matches(array $tokens, $tokenIndex)
    {
        if (!$this->isAtBeginningOfDocument($tokens, $tokenIndex)) {
            return false;
        }

        if (!$this->isFollowedByTwoNewLines($tokens, $tokenIndex)) {
            return false;
        }

        return true;
    }

    private function isAtBeginningOfDocument(array $tokens, $tokenIndex)
    {
        $sequence = SequenceBuilder::create()
            ->lookingBackward()
            ->expect()
                ->any()
                ->token(T_WHITESPACE, "\n")
            ->then()
                ->exactly(1)
                ->token(T_OPEN_TAG)
            ->build();

        return $sequence->matches($tokens, $tokenIndex);
    }

    private function isFollowedByTwoNewLines(array $tokens, $tokenIndex)
    {
        $sequence = SequenceBuilder::create()
            ->expect()
                ->any()
                ->token(T_DOC_COMMENT)
            ->then()
                ->atLeast(2)
                ->tokens(T_WHITESPACE, "\n")
            ->build();

        return $sequence->matches($tokens, $tokenIndex);
    }
}
```

Next the actual sniff class is pretty simple:

```php
<?php

use Matthias\PhpCodingStandard\Sniffs\Commenting\FileCommentMatcher;

class PhpCodingStandard_Sniffs_Commenting_NoFileCommentForClassSniff implements \PHP_CodeSniffer_Sniff
{
    public function register()
    {
        // this sniff will be triggered when the code sniffer encounters a T_DOC_COMMENT token
        return array(
            T_DOC_COMMENT
        );
    }

    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // create the matcher
        $matcher = new FileCommentMatcher();

        // use the matcher to determine if the current token is a file comment
        if ($matcher->matches($tokens, $stackPtr)) {
            $phpcsFile->addError('File should not have a file comment', $stackPtr);
        }
    }
}
```
