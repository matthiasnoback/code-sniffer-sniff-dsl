<?php

require __DIR__.'/../vendor/autoload.php';

// trigger autoload of tokens:
if (!class_exists('\PHP_CodeSniffer_Tokens', true)) {
    throw new \RuntimeException('Could not find PHP_CodeSniffer_Tokens class');
}