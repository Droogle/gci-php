<?php

namespace Droogle\GCI;

class TaskList implements \IteratorAggregate, \ArrayAccess
{
    protected $tasks;
    protected $totalCount;
    protected $currentPage;
    protected $api;

    /**
     * Constructs a TaskList object.
     *
     * @param TaskInterface[] $tasks
     * @param int $totalCount
     * @param int $currentPage
     * @param Client $api
     */
    public function __construct(array $tasks, $totalCount, $currentPage, Client $api)
    {
        $this->tasks = $tasks;
        $this->totalCount = $totalCount;
        $this->currentPage = $currentPage;
        $this->api = $api;
    }

    /**
     * Returns all the tasks
     *
     * @return TaskInterface[]
     */
    public function getTasks()
    {
        return $this->tasks;
    }

    /**
     * Returns the total tasks available in this request.
     * 
     * @return int
     */
    public function getTotalCount()
    {
        return $this->totalCount;
    }

    /**
     * Returns the number of tasks in this list.
     *
     * @return int
     */
    public function getListCount()
    {
        return count($this->tasks);
    }
    
    /**
     * Returns the current page
     * 
     * @return int
     */
    public function getCurrentPageNumber()
    {
        return $this->currentPage;
    }
    
    /**
     * Returns the next page's worth of task
     * 
     * @return TaskList
     */
    public function getNextPage()
    {
        return $this->api->getTasks($this->currentPage + 1);
    }

    /**
     * Returns the previous page's worth of task
     *
     * @return TaskList
     */
    public function getPreviousPage()
    {
        return $this->api->getTasks($this->currentPage - 1);
    }
    
    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->tasks);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return isset($this->tasks[$offset]);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        return $this->tasks[$offset];
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        $this->tasks[$offset] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        unset($this->tasks[$offset]);
    }
}
