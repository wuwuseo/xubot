<?php


namespace wuwuseo\xwbot;


use wuwuseo\xwbot\Commands\Menu;

class Bot extends Command
{
    public function __construct($keyword = '',$config = [])
    {
        if(!empty($keyword)){
            $this->keyword = $keyword;
        }
        if(empty($config)){
            $this->config = require_once __DIR__.DIRECTORY_SEPARATOR.'config.php';
        } else {
            $this->config = $config;
        }
        foreach ($this->config['commands'] as $key=>$item){
            $this->create($key,$item);
        }

    }
}