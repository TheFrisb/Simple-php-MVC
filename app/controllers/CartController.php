<?php

namespace App\controllers;

use Core\ApiController;
use Core\Application;
use Core\BaseController;
use Core\Response;

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
            throw new \Exception("Empty cart");
        }
    }




}