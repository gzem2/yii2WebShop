<?php
return [
    'purchaseProduct' => [
        'type' => 2,
        'description' => 'Purchase product',
    ],
    'manageProduct' => [
        'type' => 2,
        'description' => 'Manage product',
    ],
    'manageProductCategory' => [
        'type' => 2,
        'description' => 'Manage product category',
    ],
    'customer' => [
        'type' => 1,
        'children' => [
            'purchaseProduct',
        ],
    ],
    'admin' => [
        'type' => 1,
        'children' => [
            'manageProduct',
            'manageProductCategory',
        ],
    ],
];
