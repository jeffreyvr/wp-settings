<?php

namespace Jeffreyvr\WPSettings;

use Jeffreyvr\WPSettings\Section;
use Jeffreyvr\WPSettings\WPSettings;

class Tab
{
    public WPSettings $settings;
    public $title;
    public $slug;
    public $sections = [];

    public function __construct($settings, $title, $slug = null)
    {
        $this->title = $title;
        $this->settings = $settings;

        if ($this->slug === null) {
            $this->slug = sanitize_title($title);
        }
    }

    public function add_section($title, $slug = null)
    {
        $section = new Section($this, $title, $slug);

        $this->sections[] = $section;

        return $section;
    }
}
