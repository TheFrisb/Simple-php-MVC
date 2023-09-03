<?php

namespace App\controllers;

use App\models\Product;
use App\services\FileUploader;
use Core\ApiController;

class ShopManagerController extends ApiController
{
    public function deleteProduct(){
        $productId = $this->getPostedProductId();

        if(!is_numeric($productId)){
            return $this->jsonResponse([
                'success' => 'error',
                'message' => 'Product ID is not a valid integer'
            ], 400);
        }
        $product = Product::get(intval($productId));
        if($product){
            $product->delete();
            return $this->jsonResponse([
                'success' => 'success',
                'message' => 'Product is deleted!'
            ], 200);
        }

        return $this->jsonResponse([
            'success' => 'error',
            'message' => 'Product could not be deleted!'
        ], 400);
    }

    public function updateProduct(){
        $productId = $this->getPostedProductId();
        $sale_price = $this->getPost("sale_price");
        $regular_price = $this->getPost("regular_price");
        if(!is_numeric($productId) || !is_numeric($sale_price) || !is_numeric($regular_price)){
            return $this->jsonResponse([
                'success' => 'error',
                'message' => 'Product could not be updated!'
            ], 400);
        }

        $fields = [
            'id' => intval($productId),
            'sale_price' => intval($sale_price),
            'regular_price' => intval($regular_price)
        ];

        $product = Product::get(intval($productId));
        if($product){

            $product->loadData($fields);
            $product->save();

            return $this->jsonResponse([
                'success' => 'success',
                'message' => 'Product is updated!',
                'product' => [
                    'regular_price' => $product->regular_price,
                    'sale_price' => $product->sale_price
                ]
            ], 200);
        }

        return $this->jsonResponse([
            'success' => 'error',
            'message' => 'Product could not found!'
        ], 400);

    }

    public function createProduct()
    {
        /*
         * A custom validation class would be really useful here,
         * also a custom error printing class per required field ( could be using the methods of BaseModel )
         * would also be really nice.
         * Also the file uploader class could be made more modular, taking different paths, and not using such
         * a hacky solution to generate the file path.
         * However i am too tired now, i've been at this for 10 hours, so i'll cut some corners here.
         */

        $title = $this->getPost('title');
        $regular_price = $this->getPost('regular_price');
        $sale_price = $this->getPost('sale_price');
        $thumbnail = $this->getPostFile('thumbnail');


        if ($title === '' || !intval($regular_price) || !intval($sale_price) || !isset($thumbnail)) {
            return $this->jsonResponse([
                'success' => 'error',
                'message' => 'Missing or invalid parameters.
                            Title is required and must not be empty.
                            Regular price is required and must not be empty.
                            Sale price is required and must not be empty.
                            Thumbnail image is required and must not be empty.'
            ], 400);
        }

        try {
            $fileUploader = new FileUploader($_ENV['MEDIA_ROOT_FOLDER'] . '\\products\\thumbnails\\');
            $uploadedFilePath = $fileUploader->upload($thumbnail);
        } catch (\Exception $e) {
            return $this->jsonResponse([
                'success' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }

        $fields = [
            'title' => $title,
            'regular_price' => intval($regular_price),
            'sale_price' => intval($sale_price),
            'thumbnail_path' => $fileUploader->generateFileUrl($uploadedFilePath)
        ];

        $product = new Product();
        $product->loadData($fields);
        $product->save();

        return $this->jsonResponse([
            'success' => 'success',
            'message' => 'Product is created!',
            'product' => $fields
        ], 200);
    }
}