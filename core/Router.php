<?php

namespace Core;

class Router{
    public Request $request;
    public Response $response;
    protected array $routes = [];

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function addGetRoute($path, $callback){
        $this->routes['GET'][$path] = $callback;
    }

    public function addPostRoute($path, $callback){
        $this->routes['POST'][$path] = $callback;
    }

    public function resolve(){
        $path = $this->request->url();
        $method = $this->request->method();
        $callback = $this->routes[$method][$path] ?? false;

        if ($this->isStaticFile($path)) {
            return $this->serveStaticFile($path);
        }


        if($callback === false){
            echo '<h1>404</h1>';
            echo var_dump($this->routes);
            exit;
        }

        if (is_string($callback)){
            return '??';
        }
        if (is_array($callback)){
            $callback[0] = new $callback[0]();
        }
        return call_user_func($callback);
    }


    private function isStaticFile($path)
    {
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $allowedExt = ['css', 'js', 'png', 'jpg', 'jpeg', 'gif', 'svg']; // add more as needed

        return in_array($ext, $allowedExt);
    }

    private function serveStaticFile($path)
    {
        $fullPath = Application::$ROOT_DIR . '/public' . $path; // assuming your static files are in a 'public' directory

        if (!file_exists($fullPath)) {
            echo '<h1>404</h1>';
            exit;
        }

        $mimeType = mime_content_type($fullPath);

        header("Content-Type: $mimeType");
        readfile($fullPath);
        exit;
    }



}