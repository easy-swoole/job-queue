## JobQueue

可以方便用户快速搭建多协程分布式任务处理队列

```
class Queue implements \EasySwoole\JobQueue\QueueInterface{

    function pop(float $timeout = 3): ?\EasySwoole\JobQueue\AbstractJob
    {
        
        return null;
    }

    function push(\EasySwoole\JobQueue\AbstractJob $job): bool
    {
        return true;
    }
}

$queue = new \EasySwoole\JobQueue\JobQueue(new Queue());

$http = new swoole_http_server("127.0.0.1", 9501);
$queue->attachServer($http);

$http->on("request", function ($request, $response) {
    $response->header("Content-Type", "text/plain");
    $response->end("Hello World\n");
});

$http->start();
```
