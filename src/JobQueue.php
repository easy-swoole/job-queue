<?php


namespace EasySwoole\JobQueue;


class JobQueue
{
    private $table;
    const RUNNING_NUM = 'RUNNING_NUM';
    const MAX_CURRENCY = 'MAX_CURRENCY';
    function __construct()
    {
        //创建swoole table
        //初始化 RUNNING_NUM  MAX_CURRENCY
    }

    function maxCurrency()
    {
        //从swoole table读取
    }

}