<?php

namespace Core;

use App\models\Product;

class CartSession
{
    public function __construct()
    {
        if(!isset($_SESSION['cart'])){
            $_SESSION['cart'] = [];
        }
        if(!isset($_SESSION['cartTotal'])){
            $_SESSION['cartTotal'] = 0;
        }
    }

    public function createCartItem(Product $product) : array{
        return [
            'product_id' => $product->id,
            'title' => $product->title,
            'thumbnail_path' => $product->thumbnail_path,
            'regular_price' => $product->regular_price,
            'sale_price' => $product->sale_price,
            'quantity' => 1,
            'created_at' => time()
        ];
    }

    public function isProductInCart($productId): bool
    {
        foreach($_SESSION['cart'] as $cartItem){
            if ($cartItem['product_id'] === $productId){
                return true;
            }
        }
        return false;
    }

    public function addProductToCart($productId, $quantity = 1)
    {
        $product = Product::get($productId);
        if($product === null) return null;

        if ($quantity <= 0){
            return null;
        }
        if($this->isProductInCart($productId)){
            error_log("ASD");
            return $this->AddExistingProductToCart($productId);
        }

        $cartItem = $this->createCartItem($product);
        $_SESSION['cart'][] = $cartItem;

        $this->recalculateCartTotal();
        return $cartItem;
    }
    public function AddExistingProductToCart($productId)
    {
        foreach ($_SESSION['cart'] as &$cartItem){
            if ($cartItem['product_id'] === $productId) {
                $cartItem['quantity'] += 1;
                return $cartItem;
            }
        }
        return null;
    }

    public function recalculateCartTotal(): void
    {
        $sum = 0;
        foreach ($_SESSION['cart'] as $cartItem){
            $sum += $cartItem['sale_price'] * $cartItem['quantity'];
        }
        $_SESSION['cartTotal'] = $sum;
    }

    public function updateCartItemQuantity($product_id, $newQuantity){
        if($newQuantity <= 0){
            $cartItem = $this->removeFromCart($product_id);
            if($cartItem !== null){
                $this->recalculateCartTotal();
                return $cartItem;
            }
            return null;
        }

        foreach ($_SESSION['cart'] as &$cartItem){
            if ($cartItem['product_id'] === $product_id) {
                $cartItem['quantity'] = $newQuantity;
                $this->recalculateCartTotal();
                return $cartItem;
            }
        }
        return null;
    }

    public function removeFromCart($product_id)
    {
        foreach ($_SESSION['cart'] as $key => $cartItem){
            if ($cartItem['product_id'] === $product_id) {
                $cartItem['quantity'] = 0; // used in frontend for method updateCartItemQuantity
                unset($_SESSION['cart'][$key]);
                $this->recalculateCartTotal();
                return $cartItem;
            }
        }
        return null;
    }

    public function getItems(){
        return $_SESSION['cart'];
    }

    public function getNumberOfCartItems(){
        return sizeof($_SESSION['cart']);
    }

    public function getCartTotal() {
        return $_SESSION['cartTotal'];
    }

    function clearCart(){
        $_SESSION['cart'] = [];
        $_SESSION['cartTotal'] = 0;
    }


}