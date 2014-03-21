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
        Assertion::string($type, 'Token type should be a string');
        Assertion::startsWith($type, 'T_', 'Token type should start with T_');
        Assertion::true(defined($type), 'Token type should be the name of a defined constant');

        $this->type = $type;
        $this->code = constant($type);
    }
}
