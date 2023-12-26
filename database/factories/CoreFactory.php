<?php

declare(strict_types=1);

namespace Database\Factories;

use Closure;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

abstract class CoreFactory extends Factory implements CoreFactoryInterface
{
    /**
     * Get the raw attributes of the model.
     *
     * @param  Model|null  $parent  The parent model.
     *
     * @return array The raw attributes of the model.
     */
    public function getRawAttributes(?Model $parent): array
    {
        // Use pipe method to perform operations on the states collection
        return $this->states->pipe(function (Collection $states) {
            // Check if the 'for' collection is empty
            if ($this->for->isEmpty()) {
                // Return the states collection as is
                return $states;
            }
            // Merge parent resolvers with the states collection
            return Collection::make(
                array_merge([
                    function () {
                        return $this->parentResolvers();
                    },
                ], $states->all())
            );
        })->reduce(function (array $carry, Closure | callable $state) use ($parent) {
            // Check if the state is a closure, and bind it to the current instance of the object
            if ($state instanceof Closure) {
                $state->bindTo($this);
            }
            // Call the state function with the carry and parent parameters, and merge the result with the carry
            return array_merge($carry, $state($carry, $parent));
        }, $this->definition());
    }

    /**
     * Generates a new PaymentFactory with a specified sequence.
     *
     * @param  mixed  ...$sequence  The sequence of steps for the PaymentFactory.
     *
     * @return Factory The new PaymentFactory with the specified sequence.
     */
    public function sequence(...$sequence): Factory
    {
        // Create a new Sequence object with the specified sequence
        $newSequence = new Sequence(...$sequence);
        // Set the state of the PaymentFactory to the new Sequence object.
        return $this->state($newSequence);
    }

    /**
     * Returns a new instance of PaymentFactory with the given state.
     *
     * @param  callable  $state  The state to be added to the new instance.
     *
     * @return Factory The new instance of PaymentFactory.
     */
    public function state($state): Factory
    {
        // Concatenate the given state to the existing states
        if (! is_callable($state)) {
            $newStates = $this->states->concat(source: [
                fn() => $state,
            ]);
        } else {
            $newStates = $this->states->concat(source: [
                $state,
            ]);
        }
        // Create a new instance of PaymentFactory with the concatenated states
        return $this->newInstance(arguments: [
            'states' => $newStates,
        ]);
    }
}
