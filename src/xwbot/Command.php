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
            var_dump($this->command);
            var_dump($key);
            var_dump($this->config);
            if(preg_match(sprintf($key,$this->config['prefix']),$keyword)){
                return (new $item($keyword,$this->config))->run();
            }
        }
        return false;
    }
}