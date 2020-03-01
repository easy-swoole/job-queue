<?php


namespace EasySwoole\JobQueue;

use EasySwoole\Component\Singleton;
use swoole_table;

class JobTable
{

    use Singleton;

    private $table;

    public const JOB_QUEUE_TABLE_KEY='JOB_QUEUE_TABLE_KEY';
    public const RUNNING_NUM = 'RUNNING_NUM';

    public function __construct()
    {
        $this->table = new swoole_table(1024, 0.2);
        $this->table->column(self::RUNNING_NUM, swoole_table::TYPE_INT, 1024);
        $this->table->create();
        $this->table->set(self::JOB_QUEUE_TABLE_KEY, [
            self::RUNNING_NUM => 0
        ]);
    }

    public function runningNumIncr()
    {
        return $this->table->incr(self::JOB_QUEUE_TABLE_KEY, self::RUNNING_NUM, 1);
    }

    public function runningNumDecr()
    {
        return $this->table->decr(self::JOB_QUEUE_TABLE_KEY, self::RUNNING_NUM, 1);
    }

    public function getRunningNum()
    {
        return $this->table->get(self::JOB_QUEUE_TABLE_KEY, self::RUNNING_NUM);
    }

}