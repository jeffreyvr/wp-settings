<?php

namespace Jeffreyvr\WPSettings;

use Jeffreyvr\WPSettings\Section;
use Jeffreyvr\WPSettings\Options\Text;
use Jeffreyvr\WPSettings\Options\Select;

class Option
{
    public Section $section;
    public $type;
    public $args = [];
    public $implementation;

    public function __construct($section, $type, $args = [])
    {
        $this->section = $section;
        $this->type = $type;
        $this->args = $args;

        if ($this->type === 'text') {
            $this->implementation = new Text($this->section, $args);
        } elseif ($this->type === 'select') {
            $this->implementation = new Select($this->section, $args);
        }
    }

    public function render()
    {
        echo $this->implementation->render();
    }
}
