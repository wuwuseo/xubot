<?php


namespace wuwuseo\xwbot\Commands;


class Menu extends Base
{
    public function run(){
        return <<<EOF
{$this->config['data'][self::class]['template']}
EOF;

    }
}