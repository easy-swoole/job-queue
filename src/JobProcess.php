<?php

namespace EasySwoole\JobQueue;

use EasySwoole\Component\Process\AbstractProcess;
use EasySwoole\JobQueue\JobTable;
use Swoole\Coroutine;
use Throwable;

class JobProcess extends AbstractProcess
{

    protected function run($arg)
    {

        /** @var $config JobConfig*/
        $config = new JobConfig($arg);

        go(function () use ($config){

            $queue = $config->getQueue();
            while (true){
                if($config->getMaxCurrency() > JobTable::getInstance()->getRunningNum()){
                    /** @var $job JobQueueInterface*/
                    $job = $queue->pop($config->getJobKey());
                    if($job instanceof JobAbstract){
                        JobTable::getInstance()->runningNumIncr();
                        go(function ()use($job, $queue, $config){
                            $ret = null;
                            try{
                                $ret = $job->exec();
                            }catch (Throwable $throwable){
                                $ret = $job->onException($throwable);
                            }finally {
                                if($ret === false && $job->maxTryTimes>$job->tryTimes+1){
                                    $job->tryTimes++;
                                    $queue->push($config->getJobKey(), $job);
                                }
                                JobTable::getInstance()->runningNumDecr();
                            }
                        });
                    } else {
                        Coroutine::sleep(0.5);
                    }
                }else{
                    Coroutine::sleep(0.5);
                }
            }
        });

    }

}