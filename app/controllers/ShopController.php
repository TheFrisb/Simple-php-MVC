<?php

namespace App\controllers;

use App\models\Order;
use App\models\OrderItem;
use App\models\Product;
use App\Services\Paginator;
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
        $currentPage = Application::$app->request->get['page'] ?? 1;
        $paginator = new Paginator(Product::class, (int)$currentPage, 16);
        $products = $paginator->getItems();
        $asd = OrderItem::all();


        $context = [
            'title' => 'Shop',
            'products' => $products,
            'paginator' => $paginator,
            'cartItems' => Application::$app->cartSession->getItems(),
            'cart_total' => Application::$app->cartSession->getCartTotal(),
            'number_of_cart_items' => Application::$app->cartSession->getNumberOfCartItems()
        ];
        return $this->renderTemplate('shop.html', $context);

    }

    public function thank_you(){
        $orderId = Application::$app->request->get['order_id'];
        $order = Order::get(intval($orderId));
        $orderItems = OrderItem::filter('order_id', $order->id);
        $context = [
            'title' => 'Thank you!',
            'order' => $order,
            'orderItems' => $orderItems,
            'cartItems' => Application::$app->cartSession->getItems(),
            'cart_total' => Application::$app->cartSession->getCartTotal(),
            'number_of_cart_items' => Application::$app->cartSession->getNumberOfCartItems()
        ];
        return $this->renderTemplate('thank_you.html', $context);
    }

    public function edit_products(){

        $currentPage = Application::$app->request->get['page'] ?? 1;
        $paginator = new Paginator(Product::class, (int)$currentPage, 16);
        $products = $paginator->getItems();

        $context = [
            'title' => 'Edit products',
            'products' => $products,
            'paginator' => $paginator,
            'cartItems' => Application::$app->cartSession->getItems(),
            'cart_total' => Application::$app->cartSession->getCartTotal(),
            'number_of_cart_items' => Application::$app->cartSession->getNumberOfCartItems()
        ];
        return $this->renderTemplate('edit_products.html', $context);
    }

    public function view_orders(){
        $currentPage = Application::$app->request->get['page'] ?? 1;
        $paginator = new Paginator(Order::class, (int)$currentPage, 16);
        $orders = $paginator->getItemsAsArray();

        foreach($orders as &$order){
            $order['orderItems'] = OrderItem::filter('order_id', $order['id']);
        }

        $context = [
            'title' => 'View orders',
            'orders' => $orders,
            'paginator' => $paginator,
            'cartItems' => Application::$app->cartSession->getItems(),
            'cart_total' => Application::$app->cartSession->getCartTotal(),
            'number_of_cart_items' => Application::$app->cartSession->getNumberOfCartItems()
        ];
        return $this->renderTemplate('view_orders.html', $context);
    }


}