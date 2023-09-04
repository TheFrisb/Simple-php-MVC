<?php

namespace Core;

class Request {
    public array $get = [];
    public array $post = [];
    public array $files = [];
    public array $server = [];


    public function __construct()
    {
        $this->get = $_GET;
        $this->post = $_POST;
        $this->files = $_FILES;
        $this->server = $_SERVER;
    }

    /*
     * Fetches the url of the current request
     */
    public function url(){
        return $this->server['REQUEST_URI'] ?? '/';
    }

    /*
     * More frequently used,
     * fetches the url of the current request while ignoring get parameters
     */
    public function parsedUrl(){
        $urlComponents = parse_url($this->url());
        return $urlComponents['path'] ?? '/';
    }

    public function method(){
        return $this->server['REQUEST_METHOD'];
    }

    /*
     * Searches for post data
     * returns null if not found
     */
    public function input($key, $default=null){
        return $this->post[$key] ?? $default;
    }

    /*
     * Searches for get data
     * returns null if not found
     */
    public function query($key, $default=null){
        return $this->get[$key] ?? $default;
    }

}