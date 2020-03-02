<?php


namespace EasySwoole\JobQueue;


abstract class AbstractJob
{
    protected $tryTimes = 0;
    protected $maxTryTimes = 3;
    protected $isSuccess = false;
    /*
     * 返回false表示需要重试，其余的都当初完成
     */
    abstract function exec():bool ;
    abstract function onException(\Throwable $throwable):bool;

    /**
     * @return int
     */
    public function getTryTimes(): int
    {
        return $this->tryTimes;
    }

    /**
     * @param int $tryTimes
     */
    public function setTryTimes(int $tryTimes): void
    {
        $this->tryTimes = $tryTimes;
    }

    /**
     * @return int
     */
    public function getMaxTryTimes(): int
    {
        return $this->maxTryTimes;
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->isSuccess;
    }

    /**
     * @param bool $isSuccess
     */
    public function setIsSuccess(bool $isSuccess): void
    {
        $this->isSuccess = $isSuccess;
    }


    /**
     * @param int $maxTryTimes
     */
    public function setMaxTryTimes(int $maxTryTimes): void
    {
        $this->maxTryTimes = $maxTryTimes;
    }
}