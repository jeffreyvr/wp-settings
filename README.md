<p align="center"><img src="/art/snippet.png" alt="Code Snippet" style="width:70%;"></p>

# WP Settings

*WORK IN PROGRESS*

A package that makes creating WordPress settings pages a breeze.

## Installation

```bash
composer require jeffreyvanrossum/wp-settings
```

## Usage

### Creating the settings instance

```php
$settings = new WPSettings(__('My Plugin Settings'), 'my-plugin-settings');

$settings->set_capability('manage_options');
$settings->set_option_name('my_plugin_options');

$settings->set_menu_icon('dashicons-admin-generic');
$settings->set_menu_position(5);

// Use the below method to make your settings page a submenu item.
$settings->set_menu_parent_slug('options-general.php');
```

### Adding tabs and sections

It is very easy to create tabs. Tabs are only displayed when there is more then one.

```php
$tab = $settings->add_tab(__( 'General', 'textdomain'));

$section = $tab->add_section('Section 1');
```

If you want sections to be displayed as submenu-items, you can do:

```php
$section = $tab->add_section('Section 1', ['as_link' => true]);
```

### Adding options

You may add options to sections.

#### Regular text field

```php
$section->add_option( 'text', [
    'name' => 'option_1',
    'label' => __('Option 1', 'textdomain'),
    'placeholder' => __('Fill in something', 'textdomain')
] );
```

#### Select field

```php
$section->add_option('select', [
    'name' => 'option_1',
    'label' => __( 'Option 1', 'textdomain' ),
    'options' => [
        'value_1' => 'Label 1',
        'value_2' => 'Label 2'
    ]
]);
```

Or for multiple:

```php
$section->add_option('select-multiple', [
    'name' => 'option_1',
    'label' => __('Option 1', 'textdomain'),
    'options' => [
        'value_1' => 'Label 1',
        'value_2' => 'Label 2'
    ]
] );
```

### Validation

You are able to validate an option. You may pass a callback and a feedback message. You can pass multiple validation rules.

```php
$section->add_option('text', [
    'name' => 'mailchimp_api_key',
    'label' => __('API Key', 'textdomain'),
    'validate' => [
        [
            'feedback' => __('Your API key is too short.', 'textdomain'),
            'callback' => function($value) {
                return strlen($value) > 35;
            }
        ]
    ]
]);
```

### Sanitization

You may pass a sanitization callback.

```php
$section->add_option('text', [
    'name' => 'mailchimp_api_key',
    'label' => __('API Key', 'textdomain'),
    'santitize' => function($value) {
        return sanitize_key($value);
    }
]);
```

## Contributors
* [Jeffrey van Rossum](https://github.com/jeffreyvr)
* [All contributors](https://github.com/jeffreyvr/tailpress/graphs/contributors)

## License
MIT. Please see the [License File](/LICENSE) for more information.
