<?php

namespace App\models;

use Core\objects;

class Order extends objects
{
    public string $full_name;
    public string $address;
    public int $order_total;
    public string $created_at;

    public function __construct(){}

    public static function getTableName(): string {
        return 'orders';
    }

    public function getFields(): array {
        return ['full_name', 'address', 'order_total', 'created_at'];  // Updated fields
    }

    public function getRequiredFieldsWithRules(): array {
        return [
            'full_name' => true,
            'address' => true,
            'order_total' => true,
            'created_at' => true  // Updated fields
        ];
    }
}





