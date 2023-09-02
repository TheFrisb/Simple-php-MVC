<?php
namespace Core;
use \Twig\Environment;

class Application {
    public static string $ROOT_DIR;
    public static Environment $twig;
    public Router $router;
    public Request $request;
    public Database $db;
    public Response $response;

    public static Application $app;

    public function __construct($rootPath, $twigLoader){
        self::$ROOT_DIR = $rootPath;
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
        $this->db = new Database();
        self::$twig = new Environment($twigLoader, [
            'cache' => $rootPath . '/runtime/cache',
            'debug' => true,
        ]);
        self::$app = $this;

    }

    public function run(){
        echo $this->router->resolve();
    }
}