<?php

namespace Jeffreyvr\WPSettings;

use Jeffreyvr\WPSettings\Section;
use Jeffreyvr\WPSettings\Options\Text;
use Jeffreyvr\WPSettings\Options\Select;
use Jeffreyvr\WPSettings\Options\Choices;
use Jeffreyvr\WPSettings\Options\Checkbox;
use Jeffreyvr\WPSettings\Options\Textarea;
use Jeffreyvr\WPSettings\Options\WPEditor;
use Jeffreyvr\WPSettings\Options\CodeEditor;
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
            'text' => Text::class,
            'checkbox' => Checkbox::class,
            'choices' => Choices::class,
            'textarea' => Textarea::class,
            'wp-editor' => WPEditor::class,
            'code-editor' => CodeEditor::class,
            'select' => Select::class,
            'select-multiple' => SelectMultiple::class
        ]);

        $this->implementation = new $type_map[$this->type]($section, $args);
    }

    public function get_arg($key, $fallback = null)
    {
        return $this->args[$key] ?? $fallback;
    }

    public function sanitize($value)
    {
        if (\is_callable($this->get_arg('sanitize'))) {
            return $this->get_arg('sanitize')($value);
        }

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
