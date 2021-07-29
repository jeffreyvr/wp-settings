<?php

namespace Jeffreyvr\WPSettings;

use Jeffreyvr\WPSettings\Tab;

class WPSettings
{
    public $title;
    public $slug;
    public $capability;
    public $menu_icon;
    public $menu_position;
    public $option_name;
    public $tabs = [];

    public function __construct($title, $slug = null)
    {
        $this->title = $title;

        if ($this->slug === null) {
            $this->slug = sanitize_title($title);
        }
    }

    public function set_capability($capability)
    {
        $this->capability = $capability;

        return $this;
    }

    public function set_option_name($name)
    {
        $this->option_name = $name;

        return $this;
    }

    public function set_menu_icon($icon)
    {
        $this->menu_icon = $icon;

        return $this;
    }

    public function set_menu_position($position)
    {
        $this->menu_position = $position;

        return $this;
    }

    public function add_to_menu()
    {
        \add_menu_page($this->title, $this->title, $this->capability, $this->slug, [$this, 'render'], $this->menu_icon, $this->menu_position);
    }

    public function make()
    {
        add_action('admin_init', [$this, 'save']);
        add_action('admin_menu', [$this, 'add_to_menu']);
    }

    public function get_tab_by_slug($slug)
    {
        foreach ($this->tabs as $tab) {
            if ($tab->slug === $slug) {
                return $tab;
            }
        }
        return false;
    }

    public function get_active_tab()
    {
        $default = $this->tabs[0] ?? false;

        if (isset($_GET['tab'])) {
            return in_array($_GET['tab'], array_map(function ($tab) {
                return $tab->slug;
            }, $this->tabs)) ? $this->get_tab_by_slug($_GET['tab']) : $default;
        }

        return $default;
    }

    public function add_tab($title, $slug = null)
    {
        $tab = new Tab($this, $title, $slug);

        $this->tabs[] = $tab;

        return $tab;
    }

    public function should_make_tabs()
    {
        return count($this->tabs) > 1;
    }

    public function get_url()
    {
        return \admin_url("admin.php?page=$this->slug");
    }

    public function render_tab_menu()
    {
        if (! $this->should_make_tabs()) {
            return;
        }

        view('tab-menu', ['settings' => $this]);
    }

    public function render_active_sections()
    {
        view('sections', ['settings' => $this]);
    }

    public function render()
    {
        global $wp_settings_flash;

        view('settings-page', ['settings' => $this, 'flash' => $wp_settings_flash]);
    }

    public function get_options()
    {
        return get_option($this->option_name, []);
    }

    public function find_option($search_option)
    {
        foreach($this->tabs as $tab) {
            foreach ( $tab->sections as $section ) {
                foreach ( $section->options as $option ) {
                    if ( $option->args['name'] == $search_option ) {
                        return $option;
                    }
                }
            }
        }
        return false;
    }

    public function save()
    {
        if (! isset($_POST['wp_settings_trigger'])) {
            return;
        }

        if (! current_user_can($this->capability)) {
            wp_die(__('What do you think you are doing?'));
        }

        if (! isset($_POST[$this->option_name])) {
            return;
        }

        $current_options = $this->get_options();
        $new_options = $_POST[$this->option_name] ?? [];

        $new_options = apply_filters('wp_settings_new_options', $new_options, $current_options);

        foreach ($_POST[$this->option_name] as $option => $value) {
            $current_options[$option] = apply_filters("wp_settings_new_options_$option", $value, $this->find_option($option));
        }

        update_option($this->option_name, $current_options);

        global $wp_settings_flash;

        $wp_settings_flash = ['status' => 'success', 'message' => __('Saved changes!')];
    }
}
