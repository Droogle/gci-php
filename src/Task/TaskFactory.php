<?php

namespace Droogle\Gci\TaskFactory;

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
