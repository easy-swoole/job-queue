<?php


namespace EasySwoole\JobQueue;


abstract class JobAbstract
{
    public $tryTimes = 0;
    public $maxTryTimes = 3;
    /*
     * 返回false表示需要重试，其余的都当初完成
     */
    abstract function exec():bool ;
    abstract function onException(\Throwable $throwable):bool;

}