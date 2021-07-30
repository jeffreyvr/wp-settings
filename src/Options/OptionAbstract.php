<?php

namespace Jeffreyvr\WPSettings\Options;

use Jeffreyvr\WPSettings\Section;
use Jeffreyvr\WPSettings\WPSettings;

abstract class OptionAbstract
{
    public Section $section;
    public $args = [];

    public function __construct($section, $args = [])
    {
        $this->section = $section;
        $this->args = $args;
    }

    public function sanitize($value)
    {
        return $value;
    }

    public function get_arg($key, $fallback = null)
    {
        return $this->args[$key] ?? $fallback;
    }

    public function get_label()
    {
        return \esc_attr($this->get_arg('label'));
    }

    public function get_id_attribute()
    {
        return $this->get_arg('id');
    }

    public function get_name_attribute()
    {
        return $this->section->tab->settings->option_name . '[' . $this->get_arg('name') . ']';
    }

    public function get_value_attribute()
    {
        return get_option($this->section->tab->settings->option_name)[$this->get_arg('name')] ?? null;
    }

    public function render()
    {
        return;
    }
}
