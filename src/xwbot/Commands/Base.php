<?php


namespace wuwuseo\xwbot\Commands;


abstract class Base
{
    protected $config = [];

    protected $keyword = '';

    public function __construct($keyword='',$config = []){
        $this->config = $config;
        $this->keyword = $keyword;
    }

    abstract public function run();
}