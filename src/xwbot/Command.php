<?php


namespace wuwuseo\xwbot;


abstract class Command
{
    protected $command = [];

    protected $keyword = '';

    protected $config = [];

    public function create($key,$value){
        $this->command[$key] = $value;
        return $this;
    }

    public function delete($key){
        unset($this->command[$key]);
        return $this;
    }

    public function run(){
        $keyword = $this->keyword;
        foreach ($this->command as $key=>$item){
            if(preg_match(sprintf($key,$this->config['prefix']),$keyword)){
                return (new $item($keyword,$this->config,$this->data))->run();
            }
        }
        return false;
    }
}