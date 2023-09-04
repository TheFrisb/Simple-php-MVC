<?php

namespace Core;


class JsonResponse
{
    private array $data;
    private int $statusCode;


    public function __construct(array $data, int $statusCode)
    {
        $this->data = $data;
        $this->statusCode = $statusCode;
    }


    /*
     * Returns json data to be echoed, most likely in core/Router.php
     */
    public function send()
    {
        http_response_code($this->statusCode);
        header('Content-Type: application/json');
        return json_encode($this->data);
    }
}