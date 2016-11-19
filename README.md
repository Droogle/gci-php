Google Code-in API SDK for PHP
===========================
This API can be used to access [Google Code-in](http://codein.withgoogle.com)'s Task API to request, update and modify
task definition

Installation
------
Library can be installed from composer using ```composer require droogle/gci-php``` or adding
```droogle/gci-php``` to your composer dependencies

Example
------
This is a simplified example of handling tasks via the API

```php
$client = new Droogle\GCI\Client('<api key>');
$taskList = $client->getTasks(2); // Get tasks from second page
foreach ($taskList as $task) {
    echo $task->getId() . ' ' . $task->getName(); // See src/TaskInterface.php for full function list
}
$nextPage = $taskList->getNextPage();

// Get the details of a single task
$id = 123; // Task ID
$task = $client->getTask($id);
echo $task->getDescription();

// Create a new task
$task = new Droogle\GCI\Task([
    'name' => 'Test task',
    'description' => 'Adding a new task',
    'status' => Droogle\GCI\TaskInterface::STATUS_DRAFTED,
    ...
]);
$client->createTask($task);
```
License
-------
The MIT License (See LICENSE)
