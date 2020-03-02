<?php


namespace EasySwoole\JobQueue;


interface JobQueueInterface
{
    function pop($key):?JobAbstract;
    function push($key, JobAbstract $job):bool ;
}