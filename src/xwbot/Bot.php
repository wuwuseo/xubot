<?php


namespace wuwuseo\xwbot;


use wuwuseo\xwbot\Commands\Menu;

class Bot extends Command
{
    protected $type = self::class;

    public function __construct($keyword = '',$config = [])
    {
        if(!empty($keyword)){
            $this->keyword = $keyword;
        }
        $this->config = require_once __DIR__.DIRECTORY_SEPARATOR.'config.php';
        $this->config = array_merge($this->config,$config);
        foreach ($this->config['commands'] as $key=>$item){
            $this->create($key,$item);
        }

    }
}