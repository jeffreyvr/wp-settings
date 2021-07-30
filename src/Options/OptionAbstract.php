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
        if (\is_callable($this->get_arg('sanitize'))) {
            return $this->get_arg('sanitize')($value);
        }

        return $value;
    }

    public function has_error()
    {
        return $this->section->tab->settings->get_error($this->get_arg('name'));
    }

    public function validate($value)
    {
        if (empty($this->get_arg('validate'))) {
            return true;
        }

        if (is_array($this->get_arg('validate'))) {
            foreach ($this->get_arg('validate') as $validate) {
                if (! \is_callable($validate['callback'])) {
                    continue;
                }

                $valid = $validate['callback']($value);

                if (!$valid) {
                    $this->section->tab->settings->add_error($this->get_arg('name'), $validate['feedback']);

                    return false;
                }
            }

            return true;
        }

        if (\is_callable($this->get_arg('validate'))) {
            return $this->get_arg('validate')($value);
        }

        return true;
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
