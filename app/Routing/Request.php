<?php

namespace App\Routing;

class Request
{
    protected $params = [];

    protected $headers = [];

    public function __construct()
    {
        $data = array_merge($_SERVER);

        foreach ($data as $key => $item) {
            if (strpos($key, 'HTTP_') === 0) {
                $headerName = toCamelCase(str_replace('HTTP_', '', $key));
                $this->headers[$headerName] = strtolower($item);
                continue;
            }

            $this->{toCamelCase($key)} = $item;
        }

        // Get request parameters for patch and delete requests
        switch (strtolower($this->requestMethod)) {
            case 'patch':
            case 'delete':
                parse_str(file_get_contents('php://input'),$this->params);
                break;
        }

        $this->params = array_merge($this->params, $_POST, $_GET);
    }

    public function __get($name)
    {
        return $this->{$name} ?? null;
    }

    /**
     * Returns the value of key $name in $_POST and/or $_GET.
     *
     * @param $name
     * @return mixed|null
     */
    public function get($name)
    {
        return $this->params[$name] ?? null;
    }

    public function header($name, $value = null)
    {
        if (!$value) {
            return $this->headers[toCamelCase($name)] ?? null;
        }

        $this->headers[toCamelCase($name)] = $value;
    }
}
