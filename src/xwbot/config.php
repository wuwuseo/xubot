<?php

use wuwuseo\xwbot\Commands\Menu;

return [
    'prefix'=>'#',
    'commands'=>[
        "/^%s菜单/"=>Menu::class
    ]
];