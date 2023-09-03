<?php
namespace Core;
use \Twig\Environment;

class Application {

    public static Application $app;
    public static string $ROOT_DIR;
    public static Environment $twig;
    public Router $router;
    public Request $request;
    public Database $db;
    public Response $response;
    public CartSession $cartSession;



    public function __construct($rootPath, $twigLoader){
        self::$ROOT_DIR = $rootPath;
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
        $this->db = new Database();
        $this->cartSession = new CartSession();
        self::$twig = new Environment($twigLoader, [
            'cache' => $rootPath . '/runtime/cache',
            'debug' => true,
        ]);
        self::$app = $this;

    }


    public function run(): void
    {
        echo $this->router->resolve();
    }
}