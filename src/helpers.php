<?php

namespace Jeffreyvr\WPSettings;

use Jeffreyvr\WPSettings\WPSettings;

if (! function_exists('view')) {
    function view($file, $variables = [])
    {
        foreach ($variables as $name => $value) {
            ${$name} = $value;
        }

        $full_path = __DIR__ . "/../views/{$file}.php";

        if (! file_exists($full_path)) {
            return;
        }

        include __DIR__ . "/../views/{$file}.php";
    }
}
