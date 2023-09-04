<?php

namespace App\models;

use Core\DatabaseModel;

class OrderItem extends DatabaseModel
{
    public int $order_id;
    public int $product_id;
    public string $product_title;
    public int $quantity;
    public int $price;
    public int $line_total;  // Added line_total field

    public function __construct(){}

    public static function getTableName(): string {
        return 'order_items';
    }

    public static function getRelatedFieldName(): string {
        return 'order_id';
    }
    public function getFields(): array {
        return ['order_id', 'product_id', 'product_title', 'quantity', 'price', 'line_total'];
    }

    public function getRequiredFieldsWithRules(): array {
        return [
            'order_id' => true,
            'product_id' => true,
            'product_title' => true,
            'quantity' => true,
            'price' => true,
            'line_total' => true
        ];
    }
}