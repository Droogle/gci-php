<?php

namespace Droogle\GCI;

interface TaskFactoryInterface
{
    /**
     * Returns an instance of task from an array definition of a task
     *
     * @param array $task
     * @return TaskInterface
     */
    public function getTask(array $task);
}
