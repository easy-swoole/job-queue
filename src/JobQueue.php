<?php


namespace EasySwoole\JobQueue;


use EasySwoole\Component\Process\Config;
use Swoole\Server;

class JobQueue
{
    private $queue;
    private $workerNum = 3;
    private $queueName = 'job';
    /*
     * 单进程默认最大并发是128
     */
    private $maxCurrency = 128;
    /**
     * @var callable
     */
    private $onTask;

    /**
     * @var callable
     */
    private $afterTask;


    function __construct(QueueDriverInterface $jobQueue)
    {
        $this->queue = $jobQueue;
    }

    /**
     * @return QueueDriverInterface
     */
    public function getQueue(): QueueDriverInterface
    {
        return $this->queue;
    }

    /**
     * @return int
     */
    public function getWorkerNum(): int
    {
        return $this->workerNum;
    }

    /**
     * @param int $workerNum
     */
    public function setWorkerNum(int $workerNum): void
    {
        $this->workerNum = $workerNum;
    }

    /**
     * @return string
     */
    public function getQueueName(): string
    {
        return $this->queueName;
    }

    /**
     * @param string $queueName
     */
    public function setQueueName(string $queueName): void
    {
        $this->queueName = $queueName;
    }

    /**
     * @return int
     */
    public function getMaxCurrency(): int
    {
        return $this->maxCurrency;
    }

    /**
     * @param int $maxCurrency
     */
    public function setMaxCurrency(int $maxCurrency): void
    {
        $this->maxCurrency = $maxCurrency;
    }

    /**
     * @return callable
     */
    public function getOnTask(): ?callable
    {
        return $this->onTask;
    }

    /**
     * @param callable $onTask
     */
    public function setOnTask(callable $onTask): void
    {
        $this->onTask = $onTask;
    }

    /**
     * @return callable
     */
    public function getAfterTask(): ?callable
    {
        return $this->afterTask;
    }

    /**
     * @param callable $afterTask
     */
    public function setAfterTask(callable $afterTask): void
    {
        $this->afterTask = $afterTask;
    }


    public function attachServer(Server $server)
    {
        for($i = 0;$i < $this->workerNum;$i++){
            $config = new Config();
            $config->setProcessName("{$this->queueName}.{$i}");
            $config->setProcessGroup("{$this->queueName}");
            $config->setArg($this);
            $config->setEnableCoroutine(true);
            $p = new JobWorker($config);
            $server->addProcess($p->getProcess());
        }
    }
}