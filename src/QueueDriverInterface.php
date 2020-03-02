<?php


namespace EasySwoole\JobQueue;


interface QueueDriverInterface
{
    function pop(float $timeout = 3):?AbstractJob;
    function push(AbstractJob $job):bool ;
}