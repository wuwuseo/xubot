<?php


namespace wuwuseo\xwbot;


use wuwuseo\xwbot\Commands\Menu;

class Bot extends Command
{
    protected $type = self::class;

    protected $data;

    public function __construct($keyword = '',$config = [],$data)
    {
        $this->data = $data;
        if(!empty($keyword)){
            $this->keyword = $keyword;
        }
        $this->config = $config;
        foreach ($this->config['commands'] as $key=>$item){
            $this->create($key,$item);
        }

    }
}