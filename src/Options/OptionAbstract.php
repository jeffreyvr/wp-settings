<?php

namespace Jeffreyvr\WPSettings\Options;

use Jeffreyvr\WPSettings\Section;
use Jeffreyvr\WPSettings\WPSettings;
use function Jeffreyvr\WPSettings\view as view;

abstract class OptionAbstract
{
    public $section;
    public $args = [];
    public $view;

    public function __construct($section, $args = [])
    {
        $this->section = $section;
        $this->args = $args;
    }

    public function render()
    {
        return view('options/' . $this->view, ['option' => $this]);
    }

    public function has_error()
    {
        return $this->section->tab->settings->errors->get($this->get_arg('name'));
    }

    public function sanitize($value)
    {
        return sanitize_text_field($value);
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
                    $this->section->tab->settings->errors->add($this->get_arg('name'), $validate['feedback']);

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
        return $this->get_arg('id', sanitize_title($this->get_name_attribute()));
    }

    public function get_name()
    {
        return $this->get_arg('name');
    }

    public function get_name_attribute()
    {
        return $this->section->tab->settings->option_name . '[' . $this->get_arg('name') . ']';
    }

    public function get_value_attribute()
    {
        return get_option($this->section->tab->settings->option_name)[$this->get_arg('name')] ?? null;
    }
}
