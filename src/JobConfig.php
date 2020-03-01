<?php
/**
 * @CreateTime:   2020-03-01 15:27
 * @Author:       huizhang  <tuzisir@163.com>
 * @Copyright:    copyright(2020) Easyswoole all rights reserved
 * @Description:  job-queue配置
 */
namespace EasySwoole\JobQueue;

use EasySwoole\Component\Singleton;
use EasySwoole\Spl\SplBean;

class JobConfig extends SplBean
{

    use Singleton;

    protected $queue;

    protected $maxCurrency;

    protected $jobKey='JOB_QUEUE_KEY';

    /**
     * @return mixed
     */
    public function getQueue() : JobQueueInterface
    {
        return $this->queue;
    }

    /**
     * @return mixed
     */
    public function getMaxCurrency()
    {
        return $this->maxCurrency;
    }

    public function getJobKey()
    {
        return $this->jobKey;
    }

}