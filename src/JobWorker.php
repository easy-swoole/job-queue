<?php

namespace EasySwoole\JobQueue;

use EasySwoole\Component\Process\AbstractProcess;
use Swoole\Coroutine;
use Swoole\Coroutine\Channel;

class JobWorker extends AbstractProcess
{
    protected $running = 0;
    protected function run($arg)
    {
        /** @var JobQueue $jobQueue */
        $jobQueue = $arg;
        $channel = new Channel(64);
        /*
         * 任务投递采用mailbox形式，避免jobQueue耗费大量链接
         */

        Coroutine::create(function ()use($channel,$jobQueue){
            while (1){
                $ret = $channel->pop(1);
                if($ret instanceof AbstractJob){
                    $jobQueue->getQueue()->push($ret);
                }
            }
        });

        Coroutine::create(function ()use($channel,$jobQueue){
            while (1){
                if($this->running > $jobQueue->getMaxCurrency()){
                    Coroutine::sleep(0.1);
                    continue;
                }
                $task = $jobQueue->getQueue()->pop();
                if($task){
                    $this->running++;
                    /*
                     * 防止是二次回收任务
                     */
                    $task->setIsSuccess(true);
                    /*
                     * 如果被拦截了，那就废弃该任务
                     */
                    $preCall = $this->callHook($jobQueue->getOnTask(),$task);
                    if($preCall === false){
                        continue;
                    }
                    Coroutine::create(function ()use($channel,$task,$jobQueue){
                        try{
                            $ret = $task->exec();
                        }catch (\Throwable $throwable){
                            $task->setIsSuccess(false);
                            $ret = $task->onException($throwable);
                        }finally{
                            $this->running--;
                            $preCall = $this->callHook($jobQueue->getAfterTask(),$task);
                            if($preCall === false){
                                return;
                            }
                            if($ret === false && ($task->getTryTimes() < $task->getMaxTryTimes())){
                                $task->setTryTimes($task->getTryTimes() + 1);
                                $channel->push($task);
                            }
                        }
                    });
                }else{
                    Coroutine::sleep(0.1);
                }
            }
        });
    }

    protected function callHook(?callable $call,AbstractJob $job)
    {
        if($call){
            return call_user_func($call,$job);
        }
    }

}