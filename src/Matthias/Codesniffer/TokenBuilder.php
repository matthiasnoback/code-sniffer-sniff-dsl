<?php

namespace Matthias\Codesniffer;

use Assert\Assertion;

class TokenBuilder
{
    private $type;
    private $code;
    private $content;

    private $line = 0;
    private $column = 0;
    private $level = 0;
    private $conditions = array();

    public function __construct($type)
    {
        $this->setType($type);
    }

    public static function create($type)
    {
        return new static($type);
    }

    public static function createNewLine()
    {
        return static::create('T_WHITESPACE')->setContent("\n");
    }

    public static function createDocComment()
    {
        return static::create('T_DOC_COMMENT');
    }

    public static function createOpenTag()
    {
        return static::create('T_OPEN_TAG');
    }

    public static function createClass()
    {
        return static::create('T_CLASS');
    }

    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    public function build()
    {
        return array(
            'type' => $this->type,
            'code' => $this->code,
            'content' => $this->content,
            'line' => $this->line,
            'column' => $this->column,
            'level' => $this->level,
            'conditions' => $this->conditions
        );
    }

    private function setType($type)
    {
        Assertion::string($type);
        Assertion::startsWith($type, 'T_');
        Assertion::true(defined($type));

        $this->type = $type;
        $this->code = constant($type);
    }
}
