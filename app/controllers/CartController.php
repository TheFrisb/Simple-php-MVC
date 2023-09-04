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

        $productId = $this->getPostInt('productId');

        if($productId === null){
            return $this->jsonResponse([
                'success' => 'error',
                'message' => 'Product ID is not a valid integer'
            ], 400);
        }


        $result = $this->addProductToCart($productId);

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
        $productId = $this->getPostInt('productId');
        $quantity = $this->getPostInt('quantity');

        if($productId === null || $quantity === null){
            return $this->jsonResponse([
                'success' => 'error',
                'message' => 'Product ID or quantity are either missing, or not valid integers'
            ], 400);
        }

        $result = $this->updateCartItemQuantity($productId, $quantity);
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
        $productId = $this->getPostInt('productId');

        if($productId === null){
            return $this->jsonResponse([
                'success' => 'error',
                'message' => 'Product ID is not a valid integer'
            ], 400);
        }

        $result = $this->removeProductFromCart($productId);

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

        $full_name = $this->getPostString('checkoutFullName');
        $address = $this->getPostString('checkoutAddress');


        $orderFields = [
            'full_name' => $full_name,
            'address' => $address,
            'order_total' => Application::$app->cartSession->getCartTotal()
        ];
        $cartItems = $this->getCartItems();

        $order = new Order();
        $order->loadData($orderFields);
        $order->save();


        foreach ($cartItems as $cartItem) {
            $product = Product::get($cartItem['product_id']);
            if ($product !== null) {
                $orderItemFields = [
                    'order_id' => $order->id,
                    'product_id' => $cartItem['product_id'],
                    'product_title' => $cartItem['title'],
                    'quantity' => $cartItem['quantity'],
                    'price' => $cartItem['sale_price'],
                    'line_total' => $cartItem['sale_price'] * $cartItem['quantity']
                ];
                $orderItem = new OrderItem();
                $orderItem->loadData($orderItemFields);
                $orderItem->save();
            } else {
                return new \Exception("Product not found!");
            }
        }

        // Step 4: Clear the cart
        Application::$app->cartSession->clearCart();

        return Application::$app->response->redirect("/thank-you?order_id=$order->id");

    }






}