<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Core\Application;
$rootPath = dirname(__DIR__);

$twigLoader = new \Twig\Loader\FilesystemLoader($rootPath . '/app/views');
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();


$app = new Application($rootPath, $twigLoader);

$app->router->addGetRoute('/', [\App\controllers\ShopController::class, 'home']);
$app->router->addGetRoute('/thank-you/', [\App\controllers\ShopController::class, 'thank_you']);

$app->router->addGetRoute('/edit_products/', [\App\controllers\ShopController::class, 'edit_products']);
$app->router->addGetRoute('/view_orders/', [\App\controllers\ShopController::class, 'view_orders']);


$app->run();