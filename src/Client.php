<?php

namespace Droogle\Gci;

use Droogle\Gci\Exception\BadRequestException;
use Droogle\Gci\Exception\MissingApiTokenException;
use Droogle\Gci\Exception\NotFoundException;
use Droogle\Gci\Exception\RequestFailedException;
use Droogle\Gci\Task\TaskFactoryInterface\TaskFactoryInterface;
use Droogle\Gci\Task\TaskInterface;
use Droogle\Gci\Task\TaskList;
use Droogle\Gci\Task\TaskFactory;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Middleware;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\RequestInterface;

/**
 * Performs requests to GCI REST API.
 */
class Client {
  const BASE_URL = 'https://codein.withgoogle.com/api/program/current/';

  protected $apiKey;
  protected $taskFactory;
  protected $guzzle;

  /**
   * Client constructor.
   *
   * @param string $apiKey
   *   The API Key for the org admin.
   * @param TaskFactoryInterface $taskFactory
   *   A TaskFactory object.
   */
  public function __construct($apiKey, TaskFactoryInterface $taskFactory = NULL) {

    if (is_null($taskFactory)) {
      $taskFactory = new TaskFactory();
    }
    $this->taskFactory = $taskFactory;
    $this->apiKey = $apiKey;
    $this->guzzle = $this->getGuzzleClient();
  }

  /**
   * Returns the tasks at $page.
   *
   * @param int $page
   *   The pagination number.
   *
   * @return TaskList
   *   A list of tasks.
   *
   * @throws MissingApiTokenException
   * @throws NotFoundException
   * @throws RequestFailedException
   */
  public function getTasks($page = 1) {

    $response = $this->request('tasks', 'GET', [
      'page' => abs((int) $page),
    ]);

    $tasks = [];
    foreach ($response['results'] as $task) {
      $tasks[] = $this->taskFactory->getTask($task);
    }

    return new TaskList($tasks, (int) $response['count'], $page, $this);
  }

  /**
   * Returns the details of a single task.
   *
   * @param int $id
   *   The task id.
   *
   * @return TaskInterface
   *   A task object.
   *
   * @throws MissingApiTokenException
   * @throws NotFoundException
   * @throws RequestFailedException
   * @throws BadRequestException
   */
  public function getTask($id) {

    $response = $this->request('tasks/' . $id, 'GET');
    return $this->taskFactory->getTask($response);
  }

  /**
   * Creates a new task.
   *
   * @param TaskInterface $task
   *   A task object.
   *
   * @return mixed
   *   The request response.
   *
   * @throws MissingApiTokenException
   * @throws NotFoundException
   * @throws RequestFailedException
   * @throws BadRequestException
   */
  public function createTask(TaskInterface $task) {

    return $this->request('tasks', 'POST', [], json_encode($task->getProperties()));
  }

  /**
   * Updates an existing task.
   *
   * @param TaskInterface $task
   *   A task object.
   *
   * @return mixed
   *   The request response.
   *
   * @throws MissingApiTokenException
   * @throws NotFoundException
   * @throws RequestFailedException
   * @throws BadRequestException
   */
  public function updateTask(TaskInterface $task) {

    return $this->request('tasks/' . $task->getId(), 'PUT', [], json_encode($task->getProperties()));
  }

  /**
   * Deletes an existing task.
   *
   * @param TaskInterface $task
   *   A task object.
   *
   * @return mixed
   *   The request response.
   *
   * @throws MissingApiTokenException
   * @throws NotFoundException
   * @throws RequestFailedException
   * @throws BadRequestException
   */
  public function deleteTask(TaskInterface $task) {

    return $this->request('tasks/' . $task->getId(), 'DELETE');
  }

  /**
   * Returns the API key.
   *
   * @return string
   *   The API Key.
   */
  public function getApiKey() {

    return $this->apiKey;
  }

  /**
   * Performs requests to GCI API.
   *
   * @param string $endpoint
   *   The endpoint for the request.
   * @param string $method
   *   The request method.
   * @param array $queryParams
   *   The request parameters.
   * @param string $body
   *   The request body.
   *
   * @return mixed
   *   The request response.
   *
   * @throws MissingApiTokenException
   * @throws NotFoundException
   * @throws RequestFailedException
   * @throws BadRequestException
   */
  protected function request($endpoint, $method = 'GET', $queryParams = [], $body = '') {

    // GCI API requires a trailing slash with every request.
    $response = $this->guzzle->request($method, $endpoint . '/', [
      RequestOptions::QUERY => $queryParams,
      RequestOptions::BODY => $body,
    ]);
    $statusCode = $response->getStatusCode();

    if ($statusCode == 200 || $statusCode == 201) {
      $body = json_decode($response->getBody(), TRUE);
      return $body;
    }
    elseif ($statusCode == 204) {
      return TRUE;
    }
    elseif ($statusCode == 400) {
      echo $response->getBody();
      throw new BadRequestException();
    }
    elseif ($statusCode == 401) {
      throw new MissingApiTokenException();
    }
    elseif ($statusCode == 404) {
      throw new NotFoundException();
    }
    else {
      throw new RequestFailedException();
    }
  }

  /**
   * Returns a Guzzle client with appropriate headers set for requests.
   *
   * @return GuzzleHttpClient
   *   The GuzzleHTTPClient object.
   */
  protected function getGuzzleClient() {

    $headerMiddleware = function (RequestInterface $request) {
      return $request
                ->withHeader('Authorization', 'Bearer ' . $this->getApiKey())
                ->withHeader('Content-type', 'application/json');
    };
    $headerMiddleware->bindTo($this);

    $stack = new HandlerStack();
    $stack->setHandler(new CurlHandler());
    $stack->push(Middleware::mapRequest($headerMiddleware));

    $client = new GuzzleHttpClient([
      'base_uri' => self::BASE_URL,
      'handler' => $stack,
    ]);

    return $client;
  }

}
