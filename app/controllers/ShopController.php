<?php

namespace App\controllers;

use Core\Application;
use Core\BaseController;
class ShopController extends BaseController
{
    public function home(){
        $context = [
            'title' => 'Shop'
        ];
        $this->renderTemplate('shop.html', $context);
    }

    public function thank_you(){
        $context = [
            'title' => 'Thank you!'
        ];
        $this->renderTemplate('thank_you.html', $context);
    }

    public function edit_products(){
        include Application::$ROOT_DIR . '/app/views/thank_you.php';
    }

    public function view_orders(){
        include Application::$ROOT_DIR . '/app/views/view_orders.php';
    }


}