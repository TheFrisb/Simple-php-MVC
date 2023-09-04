<?php

namespace App\controllers;

use App\models\Order;
use App\models\OrderItem;
use App\models\Product;
use Core\ApiController;
use Core\Application;


class CartController extends ApiController
{

    public function addToCart(){

        $productId = $this->getPostedProductId();

        if(!is_numeric($productId)){
            return $this->jsonResponse([
                'success' => 'error',
                'message' => 'Product ID is not a valid integer'
            ], 400);
        }


        $result = $this->addProductToCart(intval($productId));

        if ($result !== null) {
            return $this->jsonResponse([
                'success' => true,
                'message' => 'Product added to cart',
                'product' => $result,
                'cartTotal' => $this->getCartTotal(),
                'totalItems' => $this->getCartNumberOfItems()
            ], 200);
        } else {
            return $this->jsonResponse(['success' => false, 'message' => 'Could not add product'], 400);
        }
    }

    public function updateCartItem(){
        $productId = $this->getPostedProductId();
        $quantity = $this->getPostedProductQuantity();

        if(!is_numeric($productId) && is_numeric($quantity)){
            return $this->jsonResponse([
                'success' => 'error',
                'message' => 'Product ID is not a valid integer'
            ], 400);
        }

        $result = $this->updateCartItemQuantity(intval($productId), intval($quantity));
        if ($result !== null) {
            return $this->jsonResponse([
                'success' => true,
                'message' => 'Product updated',
                'product' => $result,
                'cartTotal' => $this->getCartTotal(),
                'totalItems' => $this->getCartNumberOfItems()
            ], 200);
        } else {
            return $this->jsonResponse(['success' => false, 'message' => 'Could not update product'], 400);
        }

    }

    public function removeCartItem() {
        $productId = $this->getPostedProductId();

        if(!is_numeric($productId)){
            return $this->jsonResponse([
                'success' => 'error',
                'message' => 'Product ID is not a valid integer'
            ], 400);
        }

        $result = $this->removeProductFromCart(intval($productId));

        if ($result !== null) {
            return $this->jsonResponse([
                'success' => true,
                'message' => 'Product removed from cart',
                'product' => $result,
                'cartTotal' => $this->getCartTotal(),
                'totalItems' => $this->getCartNumberOfItems()
            ], 200);
        } else {
            return $this->jsonResponse(['success' => false, 'message' => 'Could not remove product'], 400);
        }


    }

    public function checkout(){
        if(empty($_SESSION['cart'])){
            return new \Exception("Empty cart");
        }

        $order = new Order();
        $order->full_name = 'ASD';
        $order->address = 'ASD';
        $order->order_total = $this->getCartTotal();
        $order->created_at = date('Y-m-d H:i:s');
        $order->save();


        foreach ($_SESSION['cart'] as $key => $cartItem) {
            $product = Product::get($cartItem['product_id']);
            if ($product) {
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $cartItem['product_id'];
                $orderItem->quantity = $cartItem['quantity'];
                $orderItem->price = $cartItem['sale_price'];
                $orderItem->line_total = $cartItem['sale_price'] * $cartItem['quantity'];
                $orderItem->save();
            }
        }

        // Step 4: Clear the cart
        Application::$app->cartSession->clearCart();

        return $this->jsonResponse([
            'success' => true,
            'message' => 'Order successfully created',
            'orderId' => $order->id
        ], 200);

    }




}