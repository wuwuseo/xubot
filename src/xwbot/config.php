<?php

use wuwuseo\xwbot\Commands\Menu;
use wuwuseo\xwbot\Commands\CheckIn;

return [
    'prefix'=>'#',
    'commands'=>[
        "/^%s菜单/"=>Menu::class
    ],
    'data'=>[
        Menu::class=>[
            'template'=>"菜单TODO"
        ]
    ]
];