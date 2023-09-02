<?php
require_once __DIR__ . '/vendor/autoload.php';
use Core\Application;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();


$app = new Application(__DIR__);

$app->db->migrate();

$app->run();