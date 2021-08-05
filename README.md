<p align="center"><img src="/art/snippet.png" alt="Code Snippet" style="width:60%;"></p>

# WP Settings

This package aims to make it easier to create settings pages for WordPress plugins. Typically, you would use the [Settings API](https://developer.wordpress.org/plugins/settings/settings-api/) or write something custom. While the Settings API works, there is still quite a lot to set up. You still need to write the HTML for your options for example. And it gets quite complicated if you want to add tabs and tab-sections. See this [comparison](https://www.youtube.com/watch?v=WoBRuLgacDo).

**This package is still under initial development. Use with caution untill the release of a stable version.**

## Installation

```bash
composer require jeffreyvanrossum/wp-settings dev-master
```

## Usage

### Basic example

```php
use Jeffreyvr\WPSettings\WPSettings;

$settings = new WPSettings(__('My Plugin Settings'));

$tab = $settings->add_tab(__( 'General', 'textdomain'));

$section = $tab->add_section('MailChimp');

$section->add_option('text', [
    'name' => 'mailchimp_api_key',
    'label' => __('API Key', 'textdomain')
]);

$settings->make();
```

### Creating the settings instance

```php
$settings = new WPSettings(__('My Plugin Settings'));
```

By default, the page slug is created by sanitizing the title. You may pass a specific slug as the second parameter of the constructor.

Other methods for this class:

```php
$settings->set_capability('manage_options');
$settings->set_option_name('my_plugin_options');
$settings->set_menu_icon('dashicons-admin-generic');
$settings->set_menu_position(5);
$settings->set_menu_parent_slug('options-general.php');
```

### Tabs

Tabs are only displayed when there is more then one.

```php
$settings->add_tab(__( 'General', 'textdomain'));
```

### Sections

You can call the `add_section` method from an instance of `Tab`. You can also call it from the `WPSettings` instance. It will then be added to the last created `Tab`.

```php
$tab->add_section('Section 1');
```

If you want sections to be displayed as submenu-items, you can do:

```php
$tab->add_section('Section 1', ['as_link' => true]);
```

Note that this only has an effect when you have more then one `as_link` section.

### Options

To add an option, you either call the `add_option` method from an instance of `Section`. You may also call `add_option` from the `WPSettings` instance. The option will then be added to the last created section.

#### Text

```php
$section->add_option('text', [
    'name' => 'option_1',
    'label' => __('Option 1', 'textdomain')
]);
```

#### Textarea

```php
$section->add_option('textarea', [
    'name' => 'option_1',
    'label' => __('Option 1', 'textdomain')
]);
```

#### Select

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

#### Select Multiple

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

#### WP Editor

```php
$section->add_option('wp-editor', [
    'name' => 'option_1',
    'label' => __('Option 1', 'textdomain')
] );
```

#### Code Editor

```php
$section->add_option('code-editor', [
    'name' => 'option_1',
    'label' => __('Option 1', 'textdomain')
] );
```

#### Color

Will be implemented later.

#### Media

Will be implemented later.

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

### Adding a custom option type

To add an custom option type, you can use the `wp_settings_option_type_map` filter.

```php
add_filter('wp_settings_option_type_map', function($options){
    $options['custom'] = YourCustomOption::class;
    return $options;
});
```

You will need to create a class for your custom option type.

```php
use Jeffreyvr\WPSettings\Options\OptionAbstract;

class YourCustomOption extends OptionAbstract
{
    public $view = 'custom-option';

    public function render()
    {
        echo 'Your custom option HTML';
    }
}
```

Once registered, you can then use your option type like so:

```php
$settings->add_option('custom-option', [
    'name' => 'your_option_name',
    'label' => __('Your label')
]);
```

## Contributors
* [Jeffrey van Rossum](https://github.com/jeffreyvr)
* [All contributors](https://github.com/jeffreyvr/wp-settings/graphs/contributors)

## License
MIT. Please see the [License File](/LICENSE) for more information.