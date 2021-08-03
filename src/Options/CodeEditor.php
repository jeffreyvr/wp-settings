<?php

namespace Jeffreyvr\WPSettings\Options;

use function Jeffreyvr\WPSettings\view as view;
use Jeffreyvr\WPSettings\Options\OptionAbstract;

class CodeEditor extends OptionAbstract
{
    public $view = 'code-editor';

    public function __construct($section, $args = [])
    {
        add_action('admin_enqueue_scripts', [$this, 'enqueue']);

        parent::__construct($section, $args);
    }

    public function enqueue()
    {
        wp_enqueue_script('wp-theme-plugin-editor');
        wp_enqueue_style('wp-codemirror');

        $settings_name = str_replace('-', '_', $this->get_id_attribute());

        wp_localize_script('jquery', $settings_name, wp_enqueue_code_editor(['type' => $this->get_arg('editor_type', 'text/html')]));

        wp_add_inline_script('wp-theme-plugin-editor', 'jQuery(function($){ wp.codeEditor.initialize($("#'.$this->get_id_attribute().'"), '.$settings_name.'); });');
    }

    public function sanitize($value)
    {
        return $value;
    }
}
