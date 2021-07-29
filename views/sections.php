<form method="post" action="<?php echo $settings->get_url(); ?>">

    <?php foreach ($settings->get_active_tab()->sections as $section) { ?>
        <h2><?php echo $section->title; ?></h2>

        <?php foreach ($section->options as $option) { ?>
            <?php echo $option->render(); ?>
        <?php } ?>

    <?php } ?>

    <input type="hidden" name="wp_settings_trigger" value="1">

    <?php submit_button(); ?>

</form>
