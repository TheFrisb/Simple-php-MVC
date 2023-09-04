<?php

namespace Core;

use JetBrains\PhpStorm\NoReturn;

/**
 *  The router class, routing incoming requests,
 *  responsible for calling the desired functions.
 */
class Router{
    public Request $request;
    public Response $response;

    /**
     * @var array | Array of routes
     */
    protected array $routes = [];


    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * @param $path | The url path for get method
     * @param $callback | The callback to be executed when encountered with this url
     * @return void
     */
    public function addGetRoute($path, $callback){
        $this->routes['GET'][$path] = $callback;
    }

    /**
     * @param $path | The url path for post method
     * @param $callback | The callback to be executed when encountered with this url
     * @return void
     */
    public function addPostRoute($path, $callback){
        $this->routes['POST'][$path] = $callback;
    }

    /**
     * @param $method
     * @return array
     */
    public function getRoutes($method): array {
        return $this->routeMap[$method] ?? [];
    }

    /**
     * @return false|mixed|string|void
     */
    public function resolve(){

        $path = $this->request->parsedUrl();
        $method = $this->request->method();
        $callback = $this->routes[$method][$path] ?? false;

        if ($this->isStaticFile($path)) {
            // This exits at the end, this would be better implement with a return statement
            $this->serveStaticFile($path);

        }

        /*
         * If path is not found for the specific method in the route map,
         * callback is false and 404 is returned
         */
        if($callback === false){
            http_response_code(404);
            echo '<h1>404</h1>';
            var_dump($this->routes); // debugging purposes...
            exit;
        }

        // Could be handy for static views
        if (is_string($callback)){
            // implement
        }
        /* The code bellow finds the controller and executes the method that was set for
         *   the route.
         */
        if (is_array($callback)){
            $callback[0] = new $callback[0]();
        }
        $result = call_user_func($callback);

        /*
         * If the controller returns a JsonResponse
         * return it to be echoed
         */
        if($result instanceof JsonResponse){
            return $result->send();

        }

        /*
         * Exceptions are not currently handled in this version
         */
        if($result instanceof \Exception){
            http_response_code(500);
            echo '<h1>500 Internal server error<br></h1>';
            return $result->getMessage();

        }
        return $result;
    }


    /**
     * Check if static file
     * @param $path
     * @return bool
     */
    private function isStaticFile($path)
    {

        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $allowedExt = ['css', 'js', 'png', 'jpg', 'jpeg', 'gif', 'svg']; // add more as needed

        return in_array($ext, $allowedExt);
    }

    /**
     * Serve the static file
     * @param $path
     * @return void
     */
    private function serveStaticFile($path)
    {
        $fullPath = Application::$ROOT_DIR . '/public' . $path; // assuming your static files are in a 'public' directory

        if (!file_exists($fullPath)) {
            http_response_code(404);
            echo '<h1>404</h1>';
            exit;
        }

        $mimeType = mime_content_type($fullPath);

        header("Content-Type: $mimeType");
        readfile($fullPath);

        exit;
    }



}