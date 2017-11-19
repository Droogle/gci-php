<?php

namespace Droogle\Gci\Task;

/**
 * Task object factory.
 */
class TaskFactory implements TaskFactoryInterface {

  /**
   * {@inheritdoc}
   */
  public function getTask(array $task) {

    return new Task($task);
  }

}
