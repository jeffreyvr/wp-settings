# WP Settings

A package that makes creating WordPress settings pages a breeze.

## Installation

```bash
composer require jeffreyvanrossum/wp-settings
```

## Usage

```php
$settings = new WPSettings( __('My Plugin Settings'), 'my-plugin-settings' );

$settings->set_capability( 'manage_options' );
$settings->set_option_name( 'my_plugin_options' );

$settings->set_menu_parent_slug( 'options-general.php' );
$settings->set_menu_icon( 'dashicons-admin-generic' );
$settings->set_menu_position( 'dashicons-admin-generic' );

$tab = $settings->add_tab( __( 'General', 'textdomain' ) );

$section = $tab->add_section( 'Section 1' );

$section->add_option( 'text', [
    'name' => 'option_1',
    'label' => __( 'Option 1', 'textdomain' ),
    'placeholder' => __( 'Fill in something', 'textdomain' )
] );

$settings->add_tab( __( 'Styling', 'textdomain' ) );

$settings->add_tab( __( 'API', 'textdomain' ), 'api-settings' );
```
