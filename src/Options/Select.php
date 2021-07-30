<?php

namespace Jeffreyvr\WPSettings\Options;

use function Jeffreyvr\WPSettings\view as view;
use Jeffreyvr\WPSettings\Options\OptionAbstract;

class Select extends OptionAbstract
{
    public function render()
    {
        return view('options/select', ['option' => $this]);
    }
}