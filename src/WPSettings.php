<?php

namespace Jeffreyvr\WPSettings;

use Jeffreyvr\WPSettings\Tab;

class WPSettings
{
    public $title;
    public $slug;
    public $parent_slug;
    public $capability = 'manage_options';
    public $menu_icon;
    public $menu_position;
    public $option_name;
    public $tabs = [];
    public $errors;

    public function __construct($title, $slug = null)
    {
        $this->title = $title;
        $this->option_name = strtolower(str_replace('-', '_', sanitize_title($this->title)));
        $this->errors = new \WP_Error;

        if ($this->slug === null) {
            $this->slug = sanitize_title($title);
        }
    }

    public function set_menu_parent_slug($slug)
    {
        $this->parent_slug = $slug;

        return $this;
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
        if ($this->parent_slug) {
            \add_submenu_page($this->parent_slug, $this->title, $this->title, $this->capability, $this->slug, [$this, 'render'], $this->menu_position);
        } else {
            \add_menu_page($this->title, $this->title, $this->capability, $this->slug, [$this, 'render'], $this->menu_icon, $this->menu_position);
        }
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
        if ($this->parent_slug) {
            return \admin_url($this->parent_slug . "?page=$this->slug");
        }

        return \admin_url("admin.php?page=$this->slug");
    }

    public function get_full_url()
    {
        $params = [];

        if ($active_tab = $this->get_active_tab()) {
            $params['tab'] = $active_tab->slug;

            if ($active_section = $active_tab->get_active_section()) {
                $params['section'] = $active_section->slug;
            }
        }

        return add_query_arg($params, $this->get_url());
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
        view('settings-page', ['settings' => $this]);
    }

    public function get_options()
    {
        return get_option($this->option_name, []);
    }

    public function find_option($search_option)
    {
        foreach ($this->tabs as $tab) {
            foreach ($tab->sections as $section) {
                foreach ($section->options as $option) {
                    if ($option->args['name'] == $search_option) {
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
            $_option = $this->find_option($option);

            $valid = $_option->validate($value);

            if (!$valid) {
                continue;
            }

            $value = $_option->sanitize($value);

            $current_options[$option] = apply_filters("wp_settings_new_options_$option", $value, $_option);
        }

        update_option($this->option_name, $current_options);

        global $wp_settings;

        $wp_settings[$this->option_name]['flash'] = ['status' => 'success', 'message' => __('Saved changes!')];
    }

    public function get_flash()
    {
        global $wp_settings;

        return $wp_settings[$this->option_name]['flash'] ?? null;
    }

    public function get_errors()
    {
        global $wp_settings;

        return $wp_settings[$this->option_name]['errors'];
    }

    public function get_error($key)
    {
        $errors = $this->get_errors();

        if (!is_wp_error($errors)) {
            return;
        }

        return $errors->get_error_message($key);
    }

    public function add_error($key, $message)
    {
        global $wp_settings;

        $this->errors->add($key, $message);

        $wp_settings[$this->option_name]['errors'] = $this->errors;
    }
}
