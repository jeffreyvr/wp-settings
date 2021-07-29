<?php

namespace Jeffreyvr\WPSettings;

use Jeffreyvr\WPSettings\Tab;
use Jeffreyvr\WPSettings\Option;

class Section
{
    public Tab $tab;
    public $title;
    public $slug;
    public $options = [];

    public function __construct($tab, $title, $slug = null)
    {
        $this->tab = $tab;
        $this->title = $title;

        if ($this->slug === null) {
            $this->slug = sanitize_title($title);
        }
    }

    public function add_option($type, $args = [])
    {
        $option = new Option($this, $type, $args);

        $this->options[] = $option;

        return $option;
    }
}
