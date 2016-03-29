<?php

namespace Dragooon\GCI;

use DateTime;

class Task implements TaskInterface
{
    protected $properties;

    /**
     * Task constructor.
     *
     * @param array $properties
     */
    public function __construct(array $properties)
    {
        $this->properties = $properties;
    }

    /**
     * Returns any property associated with this task
     *
     * @param $key
     * @return mixed
     */
    protected function get($key)
    {
        return isset($this->properties[$key]) ? $this->properties[$key] : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->get('id');
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->get('name');
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return $this->get('description');
    }

    /**
     * {@inheritdoc}
     */
    public function getCategories()
    {
        return $this->get('categories');
    }

    /**
     * {@inheritdoc}
     */
    public function getTags()
    {
        return $this->get('tags');
    }

    /**
     * {@inheritdoc}
     */
    public function getMaxInstances()
    {
        return $this->get('max_instances');
    }

    /**
     * {@inheritdoc}
     */
    public function getStatus()
    {
        return $this->get('status');
    }

    /**
     * {@inheritdoc}
     */
    public function getIsBeginner()
    {
        return $this->get('is_beginner');
    }

    /**
     * {@inheritdoc}
     */
    public function getTimeToCompleteInDays()
    {
        return $this->get('time_to_complete_in_days');
    }

    /**
     * {@inheritdoc}
     */
    public function getLastModified()
    {
        return new DateTime($this->get('last_modified'));
    }

    /**
     * {@inheritdoc}
     */
    public function getPrivateMetadata()
    {
        return $this->get('private_metadata');
    }

    /**
     * {@inheritdoc}
     */
    public function getMentors()
    {
        return $this->get('mentors');
    }

    /**
     * {@inheritdoc}
     */
    public function getClaimedCount()
    {
        return $this->get('claimed_count');
    }

    /**
     * {@inheritdoc}
     */
    public function getAvailableCount()
    {
        return $this->get('available_count');
    }

    /**
     * {@inheritdoc}
     */
    public function getAssignmentsCount()
    {
        return $this->get('assignment_count');
    }

    /**
     * {@inheritdoc}
     */
    public function getInProgressCount()
    {
        return $this->get('in_progress_count');
    }

    /**
     * {@inheritdoc}
     */
    public function getCompletedCount()
    {
        return $this->get('completed_count');
    }

    /**
     * {@inheritdoc}
     */
    public function getAbandonedByMentorCount()
    {
        return $this->get('abandoned_by_mentor_count');
    }

    /**
     * {@inheritdoc}
     */
    public function getAbandonedByStudentCount()
    {
        return $this->get('abandoned_by_student_count');
    }

    /**
     * {@inheritdoc}
     */
    public function getOutOfTimeCount()
    {
        return $this->get('out_of_time_count');
    }

    /**
     * {@inheritdoc}
     */
    public function getProgramYear()
    {
        return $this->get('program_year');
    }

    /**
     * {@inheritdoc}
     */
    public function getProperties()
    {
        return $this->properties;
    }
}
