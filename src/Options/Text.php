<?php

namespace Jeffreyvr\WPSettings\Options;

use Jeffreyvr\WPSettings\Section;
use Jeffreyvr\WPSettings\WPSettings;
use function Jeffreyvr\WPSettings\view as view;

class Text
{
    public Section $section;
    public $args = [];

    public function __construct($section, $args = [])
    {
        $this->section = $section;
        $this->args = $args;
    }

    public function get_name_attribute()
    {
        return $this->section->tab->settings->option_name . '[' . $this->args['name'] . ']';
    }

    public function get_value_attribute()
    {
        return get_option($this->section->tab->settings->option_name)[$this->args['name']] ?? null;
    }

    public function render()
    {
        return view('options/text', ['option' => $this]);
    }
}