<?php
namespace App\Http;

class Request
{
    protected $server;

    protected $path;

    protected $queryParams = [];

    public function __construct($server)
    {
        $this->server = $server;
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
}
