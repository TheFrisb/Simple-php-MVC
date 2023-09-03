<?php

namespace App\controllers;

use App\models\Product;
use Core\Application;
use Core\BaseController;
class ShopController extends BaseController
{
    public function home(){

        /*
         * Middleware could be implemented to load common context variables (cartItems, cart_total, number_of_cart_items)
         * so that the code doesn't repeat too much
         * Or this context could be wrapped in a method call of the parent class or some other
         * But for debugging purposes it's easier like this.
         */
        $context = [
            'title' => 'Shop',
            'products' => Product::all(),
            'cartItems' => Application::$app->cartSession->getItems(),
            'cart_total' => Application::$app->cartSession->getCartTotal(),
            'number_of_cart_items' => Application::$app->cartSession->getNumberOfCartItems()
        ];
        return $this->renderTemplate('shop.html', $context);

    }

    public function thank_you(){
        $context = [
            'title' => 'Thank you!',
            'products' => Product::all(),
            'cartItems' => Application::$app->cartSession->getItems(),
            'cart_total' => Application::$app->cartSession->getCartTotal(),
            'number_of_cart_items' => Application::$app->cartSession->getNumberOfCartItems()
        ];
        return $this->renderTemplate('thank_you.html', $context);
    }

    public function edit_products(){
        $context = [
            'title' => 'Edit products',
            'products' => Product::all(),
            'cartItems' => Application::$app->cartSession->getItems(),
            'cart_total' => Application::$app->cartSession->getCartTotal(),
            'number_of_cart_items' => Application::$app->cartSession->getNumberOfCartItems()
        ];
        return $this->renderTemplate('edit_products.html', $context);
    }

    public function view_orders(){
        $context = [
            'title' => 'View orders',
            'products' => Product::all(),
            'cartItems' => Application::$app->cartSession->getItems(),
            'cart_total' => Application::$app->cartSession->getCartTotal(),
            'number_of_cart_items' => Application::$app->cartSession->getNumberOfCartItems()
        ];
        return $this->renderTemplate('view_orders.html', $context);
    }


}