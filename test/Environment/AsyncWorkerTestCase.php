<?php

namespace Aternos\Taskmaster\Test\Environment;

use Aternos\Taskmaster\Taskmaster;
use Aternos\Taskmaster\Test\Task\SleepTask;
use Aternos\Taskmaster\Worker\WorkerInterface;

abstract class AsyncWorkerTestCase extends WorkerTestCase
{
    abstract protected function createWorker(): WorkerInterface;

    protected function createTaskmaster(): void
    {
        $this->taskmaster = new Taskmaster();
        $this->taskmaster->addWorkers($this->createWorker(), 3);
    }

    public function testMultipleTasksRunAtTheSameTime(): void
    {
        $start = microtime(true);
        $this->addTasks(new SleepTask(10000), 9);
        $this->taskmaster->wait();
        $end = microtime(true);
        $time = ($end - $start) * 1000;
        $this->assertLessThan(80, $time);
    }
}