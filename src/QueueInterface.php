<?php


namespace EasySwoole\JobQueue;


interface QueueInterface
{
    function pop():?AbstractJob;
    function push(AbstractJob $job):bool ;
}