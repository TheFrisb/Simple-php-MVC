<?php

namespace App\models;

use Core\BaseModel;
use Core\objects;

class Product extends objects
{

    public string $title;
    public string $thumbnail_path;
    public int $regular_price;
    public int $sale_price;

    /**
     * @param string $title
     */
    public function __construct(){}



    public static function getTableName(): string {
        return 'products';
    }

    public function getFields(): array {
        return ['title', 'thumbnail_path', 'regular_price', 'sale_price'];
    }
    public function getRequiredFields() : array{
        return [
            'title' => true,
            'thumbnail_path' => true,
            'regular_price' => true,
            'sale_price' => true
        ];
    }

}