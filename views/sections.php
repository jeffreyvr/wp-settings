<form method="post" action="<?php echo $settings->get_full_url(); ?>">
    <?php Jeffreyvr\WPSettings\view('section-menu', compact('settings')); ?>

    <?php foreach ($settings->get_active_tab()->get_active_sections() as $section) { ?>
        <?php Jeffreyvr\WPSettings\view('section', compact('section')); ?>
    <?php } ?>

    <input type="hidden" name="wp_settings_trigger" value="1">

    <?php submit_button(); ?>
</form>
