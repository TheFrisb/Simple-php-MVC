<?php

namespace Core;

class Request {
    public array $get = [];
    public array $post = [];
    public array $server = [];


    public function __construct()
    {
        $this->get = $_GET;
        $this->post = $_POST;
        $this->server = $_SERVER;
    }

    public function url(){
        return $this->server['REQUEST_URI'] ?? '/';
    }

    public function method(){
        return $this->server['REQUEST_METHOD'];
    }

    public function input($key, $default=null){
        return $this->post[$key] ?? $default;
    }

    public function query($key, $default=null){
        return $this->get[$key] ?? $default;
    }

}