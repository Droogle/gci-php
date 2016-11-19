<?php

namespace Droogle\GCI;

interface TaskInterface
{
    const STATUS_DRAFTED = 1;
    const STATUS_PUBLISHED = 2;

    /**
     * Returns the ID associated with this task
     *
     * @return int
     */
    public function getId();

    /**
     * Returns the name associated with this task
     *
     * @return string
     */
    public function getName();

    /**
     * Returns the description of this task
     *
     * @return int
     */
    public function getDescription();

    /**
     * Returns the categories of this task
     *
     * @return array
     */
    public function getCategories();

    /**
     * Returns the tags of this task
     *
     * @return array
     */
    public function getTags();

    /**
     * Returns the maximum allowed instances of this task
     *
     * @return int
     */
    public function getMaxInstances();

    /**
     * Returns current status of this task (see STATUS constants)
     *
     * @return int
     */
    public function getStatus();

    /**
     * Returns whether this task is a beginner task or not
     *
     * @return bool
     */
    public function getIsBeginner();

    /**
     * Returns the the time (in days) which is given to the student to complete this task
     *
     * @return int
     */
    public function getTimeToCompleteInDays();

    /**
     * Returns the last modified time of this task
     *
     * @return \DateTime
     */
    public function getLastModified();

    /**
     * Returns the any private metadata associated with this task
     *
     * @return string
     */
    public function getPrivateMetadata();

    /**
     * Returns the mentors associated with this task
     *
     * @return array
     */
    public function getMentors();

    /**
     * Returns the ID associated with this task
     *
     * @return int
     */
    public function getClaimedCount();

    /**
     * Returns the number of times this task has been claimed
     *
     * @return int
     */
    public function getAvailableCount();

    /**
     * Returns the number of times this task has been assigned
     *
     * @return int
     */
    public function getAssignmentsCount();

    /**
     * Returns the number of tasks currently in progress
     *
     * @return int
     */
    public function getInProgressCount();

    /**
     * Returns the number of times this task has been completed
     *
     * @return int
     */
    public function getCompletedCount();

    /**
     * Returns the number of times the task has been abandoned by a mentor
     *
     * @return int
     */
    public function getAbandonedByMentorCount();

    /**
     * Returns the number of times the task has been abandoned by a student
     *
     * @return int
     */
    public function getAbandonedByStudentCount();

    /**
     * Returns the number of times the student has run out of time
     *
     * @return int
     */
    public function getOutOfTimeCount();

    /**
     * Returns the program year associated with this task
     *
     * @return int
     */
    public function getProgramYear();

    /**
     * Returns the array representation of this task.
     *
     * @return array
     */
    public function getProperties();
}
