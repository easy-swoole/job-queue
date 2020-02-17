<?php


namespace EasySwoole\JobQueue;


abstract class AbstractJob
{
    protected $tryTimes = 0;
    protected $maxTryTimes = 3;
    /*
     * 返回false表示需要重试，其余的都当初完成
     */
    abstract function exec():bool ;
    abstract function onException(\Throwable $throwable):bool;
}