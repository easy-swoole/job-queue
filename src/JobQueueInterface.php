<?php


namespace EasySwoole\JobQueue;


interface JobQueueInterface
{
    function pop($key):?AbstractJob;
    function push($key, AbstractJob $job):bool ;
}