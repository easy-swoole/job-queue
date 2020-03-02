<?php


namespace EasySwoole\JobQueue;


interface QueueInterface
{
    function pop(float $timeout = 3):?AbstractJob;
    function push(AbstractJob $job):bool ;
}