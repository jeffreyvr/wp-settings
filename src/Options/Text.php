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

        add_filter('wp_settings_new_options_option_1', [$this, 'sanitize'], 10, 2);
    }

    public function sanitize($value, $option)
    {
        return sanitize_text_field($value);
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
