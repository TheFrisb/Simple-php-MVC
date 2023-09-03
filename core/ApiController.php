<?php

namespace Core;

class ApiController extends BaseController
{
    public function jsonResponse(array $data, int $statusCode){
        return new JsonResponse($data, $statusCode);
    }

    public function getPostFile($query){
        return Application::$app->request->files[$query];
    }

    public function getPostedProductId(){
        return Application::$app->request->post['productId'];
    }
    public function getPost($query){
        return Application::$app->request->post[$query];
    }
    public function getPostedProductQuantity()
    {
        return Application::$app->request->post['quantity'];
    }

    public function isProductInCart($productId)
    {
        return Application::$app->cartSession->isProductInCart($productId);
    }

    public function updateCartItemQuantity($productId, $newQuantity)
    {
        return Application::$app->cartSession->updateCartItemQuantity($productId, $newQuantity);
    }

    public function removeProductFromCart($productId){
        return Application::$app->cartSession->removeFromCart($productId);
    }
    public function addProductToCart($productId){
        return Application::$app->cartSession->addProductToCart($productId);
    }

    public function getCartTotal(){
        return Application::$app->cartSession->getCartTotal();
    }

    public function AddExistingProductToCart($productId){
        return Application::$app->cartSession->AddExistingProductToCart($productId);
    }

    public function getCartNumberOfItems(){
        return Application::$app->cartSession->getNumberOfCartItems();
    }
}