<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

interface CoreFactoryInterface
{
    /**
     * Add a new state transformation to the model definition.
     *
     * @param callable(array<string, mixed>, Model|null): array<string, mixed>  $state
     */
    public function state($state): Factory;

    /**
     * Generates a new PaymentFactory with a specified sequence.
     *
     * @param  mixed  ...$sequence  The sequence of steps for the PaymentFactory.
     *
     * @return Factory The new PaymentFactory with the specified sequence.
     */
    public function sequence(...$sequence): Factory;

    /**
     * Get the raw attributes of the model.
     *
     * @param  Model|null  $parent  The parent model.
     *
     * @return array The raw attributes of the model.
     */
    public function getRawAttributes(?Model $parent): array;
}
