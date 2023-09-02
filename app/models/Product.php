<?php

namespace App\models;

use Core\BaseModel;
use Core\objects;

class Product extends objects
{

    private string $title;
    private string $thumbnail_path;
    private int $regular_price;
    private int $sale_price;


    public function getTableName(): string {
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