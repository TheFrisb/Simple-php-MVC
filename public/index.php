<?php
session_start();


require_once __DIR__ . '/../vendor/autoload.php';
use Core\Application;
$rootPath = dirname(__DIR__);

$twigLoader = new \Twig\Loader\FilesystemLoader($rootPath . '/app/views');
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();


$app = new Application($rootPath, $twigLoader);

// Get routs
$app->router->addGetRoute('/home', [\App\controllers\ShopController::class, 'home']);
$app->router->addGetRoute('/thank-you', [\App\controllers\ShopController::class, 'thank_you']);
$app->router->addGetRoute('/edit-products', [\App\controllers\ShopController::class, 'edit_products']);
$app->router->addGetRoute('/view-orders', [\App\controllers\ShopController::class, 'view_orders']);

// Api routes
$app->router->addPostRoute('/api/add-to-cart', [\App\controllers\CartController::class, 'addToCart']);
$app->router->addPostRoute('/api/remove-product', [\App\controllers\CartController::class, 'removeCartItem']);
$app->router->addPostRoute('/api/update-product-quantity', [\App\controllers\CartController::class, 'updateCartItem']);

// Admin api routes
$app->router->addPostRoute('/admin/api/delete-product', [\App\controllers\ShopManagerController::class, 'deleteProduct']);
$app->router->addPostRoute('/admin/api/update-product', [\App\controllers\ShopManagerController::class, 'updateProduct']);
$app->router->addPostRoute('/admin/api/create-new-product', [\App\controllers\ShopManagerController::class, 'createProduct']);


// Start app
$app->run();

