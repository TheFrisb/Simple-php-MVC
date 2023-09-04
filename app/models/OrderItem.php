<?php

namespace App\models;

use Core\objects;

class OrderItem extends objects
{
    public int $order_id;
    public int $product_id;
    public int $quantity;
    public int $price;
    public int $line_total;  // Added line_total field

    public function __construct(){}

    public static function getTableName(): string {
        return 'order_items';
    }

    public function getFields(): array {
        return ['order_id', 'product_id', 'quantity', 'price', 'line_total'];  // Included line_total
    }

    public function getRequiredFieldsWithRules(): array {
        return [
            'order_id' => true,
            'product_id' => true,
            'quantity' => true,
            'price' => true,
            'line_total' => true  // Included line_total
        ];
    }
}