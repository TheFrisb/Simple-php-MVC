<?php

namespace App\controllers;

use App\models\Product;
use App\services\FileUploader;
use Core\ApiController;

class ShopManagerController extends ApiController
{
    public function deleteProduct(){
        $productId = $this->getPostInt('productId');

        if($productId === null){
            return $this->jsonResponse([
                'success' => 'error',
                'message' => 'Product ID is not a valid integer'
            ], 400);
        }
        $product = Product::get($productId);
        if($product !== null){
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
        $productId = $this->getPostInt('productId');
        $sale_price = $this->getPostInt("sale_price");
        $regular_price = $this->getPostInt("regular_price");
        if($productId === null || $sale_price === null || $regular_price === null){
            return $this->jsonResponse([
                'success' => 'error',
                'message' => 'Product could not be updated!'
            ], 400);
        }

        $fields = [
            'id' => $productId,
            'sale_price' => $sale_price,
            'regular_price' => $regular_price
        ];

        $product = Product::get($productId);
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
         * Also the file uploader class could be made more modular, taking different paths and file types, and not using such
         * a hacky solution to generate the file path.
         * However i am too tired now, i've been at this for 10 hours, so i'll cut some corners here.
         */

        $title = $this->getPostString('title');
        $regular_price = $this->getPostString('regular_price');
        $sale_price = $this->getPostString('sale_price');
        $thumbnail = $this->getPostFile('thumbnail');


        if ($title === '' || $regular_price === null || $sale_price === null || $thumbnail === null) {
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
            'regular_price' => $regular_price,
            'sale_price' => $sale_price,
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