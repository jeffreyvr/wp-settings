<select name="<?php echo esc_attr($option->get_name_attribute()); ?>">
    <?php foreach ( $option->args['options'] as $_option ) { ?>
        <option <?php selected($option->get_value_attribute(), $_option); ?>><?php echo $_option; ?></option>
    <?php } ?>
</select>