<?php

namespace Dragooon\GCI;

use Dragooon\GCI\Exception\MissingApiTokenException;
use Dragooon\GCI\Exception\NotFoundException;
use Dragooon\GCI\Exception\RequestFailedException;
use Dragooon\GCI\Exception\UnknownServerException;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Middleware;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\RequestInterface;

class Client
{
    const BASE_URL = 'https://codein.withgoogle.com/api/program/current/';

    protected $apiKey;
    protected $taskFactory;
    protected $guzzle;

    /**
     * Client constructor.
     *
     * @param string $apiKey
     * @param TaskFactoryInterface $taskFactory
     */
    public function __construct($apiKey, TaskFactoryInterface $taskFactory = null)
    {
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
     * @return TaskList
     *
     * @throws MissingApiTokenException
     * @throws NotFoundException
     * @throws RequestFailedException
     * @throws UnknownServerException
     */
    public function getTasks($page = 1)
    {
        $response = $this->request('tasks', 'GET', [
            'page' => abs((int) $page),
        ]);

        $tasks = [];
        foreach ($response['results'] as $task) {
            $tasks[] = $this->taskFactory->getTask($task);
        }

        return new TasKList($tasks, (int) $response['count'], $page, $this);
    }

    /**
     * Returns the details of a single task
     *
     * @param int $id
     * @return TaskInterface
     *
     * @throws MissingApiTokenException
     * @throws NotFoundException
     * @throws RequestFailedException
     * @throws UnknownServerException
     */
    public function getTask($id)
    {
        $response = $this->request('tasks/' . $id, 'GET');
        return $this->taskFactory->getTask($response);
    }

    /**
     * Creates a new task
     *
     * @param TaskInterface $task
     * @return mixed
     *
     * @throws MissingApiTokenException
     * @throws NotFoundException
     * @throws RequestFailedException
     * @throws UnknownServerException
     */
    public function createTask(TaskInterface $task)
    {
        return $this->request('tasks', 'POST', [], json_encode($task->getProperties()));
    }

    /**
     * Updates an existing task
     *
     * @param TaskInterface $task
     * @return mixed
     *
     * @throws MissingApiTokenException
     * @throws NotFoundException
     * @throws RequestFailedException
     * @throws UnknownServerException
     */
    public function updateTask(TaskInterface $task)
    {
        return $this->request('tasks/' . $task->getId(), 'PUT', [], json_encode($task->getProperties()));
    }

    /**
     * Deletes an existing task
     *
     * @param TaskInterface $task
     * @return mixed
     *
     * @throws MissingApiTokenException
     * @throws NotFoundException
     * @throws RequestFailedException
     * @throws UnknownServerException
     */
    public function deleteTask(TaskInterface $task)
    {
        return $this->request('tasks/' . $task->getId(), 'DELETE');
    }

    /**
     * Returns the API key.
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * Internal function for performing any request and throwing any exceptions which may occur in that
     * request.
     *
     * @param string $endpoint
     * @param string $method
     * @param array $queryParams
     * @param string $body
     * @return mixed
     *
     * @throws MissingApiTokenException
     * @throws NotFoundException
     * @throws RequestFailedException
     * @throws UnknownServerException
     */
    protected function request($endpoint, $method = 'GET', $queryParams = [], $body = '')
    {
        // GCI API requires a trailing slash with every request
        $response = $this->guzzle->request($method, $endpoint . '/', [
            RequestOptions::QUERY => $queryParams,
            RequestOptions::BODY => $body,
        ]);
        $statusCode = $response->getStatusCode();

        if ($statusCode == 200 || $statusCode == 201) {
            $body = json_decode($response->getBody(), true);
            return $body;
        }
        elseif ($statusCode == 204) {
            return true;
        }
        elseif ($statusCode == 400) {
            throw new UnknownServerException();
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
     */
    protected function getGuzzleClient()
    {
        $headerMiddleware = function (RequestInterface $request) {
            return $request
                ->withHeader('Authorization', 'Bearer ' . $this->getApiKey())
                ->withHeader('Content-type', 'text/json');
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
