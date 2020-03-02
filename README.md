## JobQueue

可以方便用户快速搭建多协程分布式任务处理队列


## 使用

#### 自定义队列(目前还没有自带，后续增加)
实现QueueInterface接口，以spider组件的fast-cache为例
````php
<?php
/**
 * @CreateTime:   2020/2/22 下午2:55
 * @Author:       huizhang  <tuzisir@163.com>
 * @Copyright:    copyright(2020) Easyswoole all rights reserved
 * @Description:  爬虫组件默认fastcache为通信队列
 */
namespace EasySwoole\Spider\Queue;

use EasySwoole\Component\Singleton;
use EasySwoole\FastCache\Cache;
use EasySwoole\JobQueue\JobAbstract;
use EasySwoole\JobQueue\JobQueueInterface;

class FastCacheQueue implements JobQueueInterface
{

    use Singleton;

    function pop($key): ?JobAbstract
    {
        // TODO: Implement pop() method.
        $job =  Cache::getInstance()->deQueue($key);
        if (empty($job)) {
            return null;
        }
        $job = unserialize($job);
        if (empty($job)) {
            return null;
        }
        return $job;
    }

    function push($key, JobAbstract $job): bool
    {
        // TODO: Implement push() method.
        $res = Cache::getInstance()->enQueue($key, serialize($job));
        if (empty($res)) {
            return false;
        }
        return true;
    }

}

````
#### 注册组件

````php
public static function mainServerCreate(EventRegister $register)
    
    $jobQueueProcessConfig = new \EasySwoole\Component\Process\Config();
    $jobQueueProcessConfig->setProcessName('SpiderJobQueue');
    $jobQueueProcessConfig->setArg([
        'queue' => new FastCacheQueue(),
        'maxCurrency' => 最大可运行协程数,
        'jobKey' => '任务队列的key'
    ]);
    
    ServerManager::getInstance()->getSwooleServer()->addProcess((new JobProcess($jobQueueProcessConfig))->getProcess());    
}
````

#### 用户自定义任务
实现AbstractJob抽象类

#### 添加任务
````php
    FastCacheJobQueue::getInstance()->push(自定义任务);
````

