<?php

namespace Jeffreyvr\WPSettings;

use Jeffreyvr\WPSettings\Section;
use Jeffreyvr\WPSettings\Options\Text;
use Jeffreyvr\WPSettings\Options\Select;
use Jeffreyvr\WPSettings\Options\SelectMultiple;

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

        $type_map = apply_filters('wp_settings_option_type_map', [
            'text' => new Text($this->section, $args),
            'select' => new Select($this->section, $args),
            'select-multiple' => new SelectMultiple($this->section, $args)
        ]);

        $this->implementation = $type_map[$this->type];
    }

    public function sanitize($value)
    {
        return $this->implementation->sanitize($value);
    }

    public function validate($value)
    {
        return $this->implementation->validate($value);
    }

    public function render()
    {
        echo $this->implementation->render();
    }
}
