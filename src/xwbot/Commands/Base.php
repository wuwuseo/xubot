<?php


namespace wuwuseo\xwbot\Commands;


abstract class Base
{
    protected $config = [];

    protected $keyword = '';

    public function __construct($keyword='',$config = [],$data){
        $this->config = $config;
        $this->keyword = $keyword;
        $this->data = $data;
    }

    abstract public function run();
}