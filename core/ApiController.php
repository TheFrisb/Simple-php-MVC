<?php

namespace Core;

/**
 * An ApiController used by app/controllers/ShopController || ShopManagerController
 * This is just a wrapper class of existing methods,
 * and could be refactored further.
 */
class ApiController extends BaseController
{
    /**
     * @param array $data
     * @param int $statusCode
     * @return JsonResponse
     */
    public function jsonResponse(array $data, int $statusCode){
        return new JsonResponse($data, $statusCode);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function getPostFile($query){
        return Application::$app->request->files[$query];
    }

    /**
     * @param $query
     * @return int|null
     */
    public function getPostInt($query)
    {

        $key = Application::$app->request->post[$query];
        if (!is_numeric($key)) return null; // is_numeric checks for if it is null too.
        return intval($key);
    }

    /**
     * @param $query
     * @return mixed|null
     */
    public function getPostString($query){
        $key = Application::$app->request->post[$query];
        if ($key !== null) {
            return filter_var($key, FILTER_SANITIZE_STRING);
        };

        return null;
    }


    /**
     * @param $productId
     * @return bool
     */
    public function isProductInCart($productId)
    {
        return Application::$app->cartSession->isProductInCart($productId);
    }

    /**
     * @return mixed
     */
    public function getCartItems(){
        return Application::$app->cartSession->getItems();
    }

    /**
     * @param $productId
     * @param $newQuantity
     * @return mixed|null
     */
    public function updateCartItemQuantity($productId, $newQuantity)
    {
        return Application::$app->cartSession->updateCartItemQuantity($productId, $newQuantity);
    }

    /**
     * @param $productId
     * @return mixed|null
     */
    public function removeProductFromCart($productId){
        return Application::$app->cartSession->removeFromCart($productId);
    }

    /**
     * @param $productId
     * @return array|mixed|null
     */
    public function addProductToCart($productId){
        return Application::$app->cartSession->addProductToCart($productId);
    }

    /**
     * @return mixed
     */
    public function getCartTotal(){
        return Application::$app->cartSession->getCartTotal();
    }


    /**
     * @return int
     */
    public function getCartNumberOfItems(){
        return Application::$app->cartSession->getNumberOfCartItems();
    }
}