<?php

namespace Dragooon\GCI;

class TaskFactory implements TaskFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function getTask(array $task)
    {
        return new Task($task);
    }
}
