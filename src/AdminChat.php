<?php

namespace JumasCola\AdminChat;

use Encore\Admin\Extension;

class AdminChat extends Extension
{
    public $name = 'adminchat';

    public $views = __DIR__.'/../resources/views';

    public $assets = __DIR__.'/../resources/assets';

    public $menu = [
        'title' => 'Adminchat',
        'path'  => 'adminchat',
        'icon'  => 'fa-gears',
    ];
}