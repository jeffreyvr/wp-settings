*WORK IN PROGRESS*

# WP Settings

A package that makes creating WordPress settings pages a breeze.

## Installation

```bash
composer require jeffreyvanrossum/wp-settings
```

## Usage

### Creating the settings instance

```php
$settings = new WPSettings( __('My Plugin Settings'), 'my-plugin-settings' );

$settings->set_capability( 'manage_options' );
$settings->set_option_name( 'my_plugin_options' );

$settings->set_menu_icon( 'dashicons-admin-generic' );
$settings->set_menu_position( 'dashicons-admin-generic' );

// Use the below method to make your settings page a submenu item.
$settings->set_menu_parent_slug( 'options-general.php' );
```

### Adding tabs and sections

It is very easy to create tabs.

```php
$tab = $settings->add_tab( __( 'General', 'textdomain' ) );

$section = $tab->add_section( 'Section 1' );
```


### Adding options

You may add options to sections.

#### Regular text field

```php
$section->add_option( 'text', [
    'name' => 'option_1',
    'label' => __( 'Option 1', 'textdomain' ),
    'placeholder' => __( 'Fill in something', 'textdomain' )
] );
```

#### Select field

```php
$section->add_option( 'select', [
    'name' => 'option_1',
    'label' => __( 'Option 1', 'textdomain' ),
    'options' => [
        'value_1' => 'Label 1',
        'value_2' => 'Label 2'
    ]
] );
```

Or for multiple:

```php
$section->add_option( 'select-multiple', [
    'name' => 'option_1',
    'label' => __( 'Option 1', 'textdomain' ),
    'options' => [
        'value_1' => 'Label 1',
        'value_2' => 'Label 2'
    ]
] );
```