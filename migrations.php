<?php
require_once __DIR__ . '/vendor/autoload.php';
use Core\Application;

$rootPath = __DIR__;
$twigLoader = new \Twig\Loader\FilesystemLoader($rootPath . '/app/views');
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();


$app = new Application($rootPath, $twigLoader);

$app->db->migrate();

$app->run();