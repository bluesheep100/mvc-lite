<?php

namespace App\Routing;

class Response
{
    protected $body;

    public function __construct($status, $body)
    {
        $this->body = [
            'status' => $status,
            'data' => $body,
        ];
    }

    public function __toString(): string
    {
        return json_encode($this->body);
    }

    /**
     * Adds an error message to the response.
     *
     * @param $message
     * @return $this
     */
    public function withError($message)
    {
        $this->body['error'] = $message;

        return $this;
    }
}
