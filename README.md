<p align="center"><img src="/art/snippet.png" alt="Code Snippet" style="width:60%;"></p>

# WP Settings

*THIS PACKAGE IS WORK IN PROGRESS*

This package aims to make it easier to create settings pages for WordPress plugins. Typically, you would use the [Settings API](https://developer.wordpress.org/plugins/settings/settings-api/) or write something custom. While the Settings API works, there is still quite a lot to set up. You still need to write the HTML for your options for example. And it gets quite complicated if you want to add tabs and tab-sections. See this [comparison](#comparison-with-the-settings-api).

## Installation

```bash
composer require jeffreyvanrossum/wp-settings
```

## Usage

### Basic example

```php
$settings = new WPSettings(__('My Plugin Settings'), 'my-plugin-settings');

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
$settings = new WPSettings(__('My Plugin Settings'), 'my-plugin-settings');
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
$section->add_option('text', [
    'name' => 'option_1',
    'label' => __('Option 1', 'textdomain'),
    'placeholder' => __('Fill in something', 'textdomain')
]);
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

### Optionals

```php
$settings->set_capability('manage_options');

$settings->set_option_name('my_plugin_options');

$settings->set_menu_icon('dashicons-admin-generic');

$settings->set_menu_position(5);

$settings->set_menu_parent_slug('options-general.php');
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

## Comparison with the Settings API

The below example is a options page made with WordPress Settings API. This example is taken from the [WordPress documentation](https://developer.wordpress.org/plugins/settings/custom-settings-page/). I removed the comments to allow for a fair comparison.

```php
<?php
function wporg_settings_init() {
    register_setting( 'wporg', 'wporg_options' );

    add_settings_section(
        'wporg_section_developers',
        __( 'The Matrix has you.', 'wporg' ), 'wporg_section_developers_callback',
        'wporg'
    );

    add_settings_field(
        'wporg_field_pill',
        __( 'Pill', 'wporg' ),
        'wporg_field_pill_cb',
        'wporg',
        'wporg_section_developers',
        array(
            'label_for'         => 'wporg_field_pill',
            'class'             => 'wporg_row',
            'wporg_custom_data' => 'custom',
        )
    );
}

add_action( 'admin_init', 'wporg_settings_init' );

function wporg_section_developers_callback( $args ) {
    ?>
    <p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Follow the white rabbit.', 'wporg' ); ?></p>
    <?php
}

function wporg_field_pill_cb( $args ) {
    $options = get_option( 'wporg_options' );
    ?>
    <select
            id="<?php echo esc_attr( $args['label_for'] ); ?>"
            data-custom="<?php echo esc_attr( $args['wporg_custom_data'] ); ?>"
            name="wporg_options[<?php echo esc_attr( $args['label_for'] ); ?>]">
        <option value="red" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'red', false ) ) : ( '' ); ?>>
            <?php esc_html_e( 'red pill', 'wporg' ); ?>
        </option>
        <option value="blue" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'blue', false ) ) : ( '' ); ?>>
            <?php esc_html_e( 'blue pill', 'wporg' ); ?>
        </option>
    </select>
    <p class="description">
        <?php esc_html_e( 'You take the blue pill and the story ends. You wake in your bed and you believe whatever you want to believe.', 'wporg' ); ?>
    </p>
    <p class="description">
        <?php esc_html_e( 'You take the red pill and you stay in Wonderland and I show you how deep the rabbit-hole goes.', 'wporg' ); ?>
    </p>
    <?php
}

function wporg_options_page() {
    add_menu_page(
        'WPOrg',
        'WPOrg Options',
        'manage_options',
        'wporg',
        'wporg_options_page_html'
    );
}

add_action( 'admin_menu', 'wporg_options_page' );

function wporg_options_page_html() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    if ( isset( $_GET['settings-updated'] ) ) {
        add_settings_error( 'wporg_messages', 'wporg_message', __( 'Settings Saved', 'wporg' ), 'updated' );
    }

    settings_errors( 'wporg_messages' );
    ?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <form action="options.php" method="post">
            <?php
            settings_fields( 'wporg' );

            do_settings_sections( 'wporg' );

            submit_button( 'Save Settings' );
            ?>
        </form>
    </div>
    <?php
}
```

Now with the next example, the exact options page will be replicated with this package.

```php
use Jeffreyvr\WPSettings\WPSettings;

$settings = new WPSettings(__('WPOrg Second', 'textdomain'));

$tab = $settings->add_tab(__('General', 'textdomain'));

$section = $tab->add_section(__('The Matrix has you.', 'wporg'), ['description' => esc_html__('Follow the white rabbit.', 'wporg')]);

$section->add_option('select', [
        'name' => 'wporg_field_pill',
        'label' => __('Pill', 'textdomain'),
        'description' => 'You take the blue pill and the story ends. You wake in your bed and you believe whatever you want to believe.
        <br>You take the red pill and you stay in Wonderland and I show you how deep the rabbit-hole goes.',
        'options' => [
            'blue' => esc_html__('red pill', 'wporg'),
            'red' => esc_html__('blue pill', 'wporg')
        ]
    ]
);

$settings->make();
```