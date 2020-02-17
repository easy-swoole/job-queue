<?php


namespace EasySwoole\JobQueue;


use EasySwoole\Component\Process\AbstractProcess;
use Swoole\Coroutine;

class JobProcess extends AbstractProcess
{

    protected function run($arg)
    {
        /*
         * arg需要得到JobQueue句柄与QueueInterface
         */
        /** @var QueueInterface $queue */
        $queue = null;
        while ($isRun){
            if(MAX_CURRENCY > RUNNING_NUM){
                $job = $queue->pop();
                if($job instanceof AbstractJob){
                    //RUNNING_NUM + 1
                    go(function ()use($job){
                        $ret = null;
                        try{
                            $ret = $job->exec();
                        }catch (\Throwable $throwable){
                            $ret = $job->onException($throwable);
                        }finally {
                            if($ret === false){
                                //判断重试次数，是否需要回归队列
                            }
                            // RUNNING_NUM -1
                        }
                    });
                }
            }else{
                Coroutine::sleep(0.01);
            }
        }
    }
}