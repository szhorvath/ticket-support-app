<?php
namespace App\Http;

class Request
{
    protected $server;

    protected $path;

    protected $queryParams = [];

    protected $bodyParams = [];

    protected $headers;

    protected $body;


    public function __construct($server)
    {
        $this->server = $server;
        $this->method = $this->getMethod();
        $this->body = $this->getRequestBody();
        $this->bodyParams = $this->parseBody();
    }

    public function getMethod()
    {
        return $this->server['REQUEST_METHOD'];
    }

    public function getPath()
    {
        $uri = $this->server['REQUEST_URI'];
        if (strstr($this->server['REQUEST_URI'], '?')) {
            $uri = substr($uri, 0, strpos($uri, '?'));
        }
        return $this->path = '/'.trim($uri, '/');
    }

    public function getQueryParams()
    {
        if ($queryString = $this->server['QUERY_STRING'] !== '') {
            parse_str($this->server['QUERY_STRING'], $this->queryParams);
        }

        return $this->queryParams;
    }

    public function getContentType()
    {
        $result = $this->getHeader('Content-Type');

        return $result ? $result[0] : null;
    }

    public function getHeaders()
    {
        if (!function_exists('getallheaders')) {
            function getallheaders()
            {
                $headers = [];
                foreach ($this->server as $name => $value) {
                    if (substr($name, 0, 5) == 'HTTP_') {
                        $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
                    }
                }
                return $headers;
            }
        }
    }

    public function getRequestBody()
    {
        $stream = fopen('php://temp', 'w+');
        stream_copy_to_stream(fopen('php://input', 'r'), $stream);
        rewind($stream);

        return $stream;
    }

    public function parseBody()
    {
        return [];
    }
}
