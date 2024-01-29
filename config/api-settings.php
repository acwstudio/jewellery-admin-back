<?php

return [
    'limit-included' => env('API_LIMIT_RELATIONSHIPS_ITEMS', null),
    'to-one' => [
        \Illuminate\Database\Eloquent\Relations\BelongsTo::class
    ],
    'to-many' => [
        \Illuminate\Database\Eloquent\Relations\HasMany::class
    ]
];
