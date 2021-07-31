<?php

namespace Jeffreyvr\WPSettings\Options;

use function Jeffreyvr\WPSettings\view as view;
use Jeffreyvr\WPSettings\Options\OptionAbstract;

class Checkbox extends OptionAbstract
{
    public function render()
    {
        return view('options/checkbox', ['option' => $this]);
    }

    public function get_value_attribute()
    {
        return '1';
    }

    public function is_checked()
    {
        return parent::get_value_attribute();
    }
}
