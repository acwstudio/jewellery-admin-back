<?php

    declare(strict_types=1);

?>

<x-mail::message>
    # Нет моего размера

    Название продукта: {{ $data->product_name }}
    SKU: {{ $data->product_sku }}
    Размер: {{ $data->product_size ?? 'Без размера' }}

    ФИО: {{ $data->full_name }}
    E-mail: {{ $data->email }}
    Контактный телефон: {{ $data->phone }},
</x-mail::message>
