<?php

use function Pest\Livewire\livewire;
use PowerComponents\LivewirePowerGrid\Tests\{DishesCollectionTable, DishesTable, DishesTableWithJoin};

it('properly filters by "min"', function (string $component, string $field, string $theme) {
    livewire($component)
        ->call($theme)
        ->set('filters', filterNumber($field, '2', null, '', ''))
        ->assertSee('Peixada da chef Nábia')
        ->assertSee('Francesinha')
        ->assertSee('борщ')
        ->assertDontSee('Pastel de Nata');
})->with([
    [DishesTable::class, 'id', 'tailwind'],
    [DishesTable::class, 'id', 'bootstrap'],
    [DishesTableWithJoin::class, 'dishes.id', 'tailwind'],
    [DishesTableWithJoin::class, 'dishes.id', 'bootstrap'],
]);

it('properly filters by "min" - using collection')
    ->livewire(DishesCollectionTable::class)
    ->set('filters', filterNumber('price', 1.68, null, '', ''))
    ->assertSee('Name 2')
    ->assertSee('Name 3')
    ->assertSee('Name 4')
    ->assertDontSee('Name 1');

it('properly filters by "max"', function (string $component, string $field) {
    livewire($component)
        ->set('filters', filterNumber($field, null, '3', '', ''))
        ->assertSee('Pastel de Nata')
        ->assertSee('Peixada da chef Nábia')
        ->assertSee('Carne Louca')
        ->assertDontSee('Bife à Rolê');
})->with([
    [DishesTable::class, 'id'],
    [DishesTableWithJoin::class, 'dishes.id'],
]);

it('properly filters by "max" - using collection')
    ->livewire(DishesCollectionTable::class)
    ->set('filters', filterNumber('price', null, 1.68, '', ''))
    ->assertSee('Name 1')
    ->assertSee('Name 2')
    ->assertDontSee('Name 3');

it('properly filters by "min & max"', function (string $component, string $field) {
    livewire($component)
        ->set('filters', filterNumber($field, '1', '2', '', ''))
        ->assertSee('Pastel de Nata')
        ->assertSee('Peixada da chef Nábia')
        ->assertDontSee('Carne Louca');
})->with([
    [DishesTable::class, 'id'],
    [DishesTableWithJoin::class, 'dishes.id'],
]);

it('properly filters by "min & max" - using collection')
    ->livewire(DishesCollectionTable::class)
    ->set('filters', filterNumber('price', 1.68, 1.78, '', ''))
    ->assertSee('Name 2')
    ->assertSee('Name 3')
    ->assertDontSee('Name 1')
    ->assertDontSee('Name 4')
    ->assertDontSee('Name 5');

it('properly filters by "min & max" currency', function (string $component, string $field) {
    livewire($component)
        ->set('filters', filterNumber($field, '60.49', '100', '', ''))
        ->assertSee('Francesinha')
        ->assertSee('Barco-Sushi da Sueli')
        ->assertSee('Barco-Sushi Simples')
        ->assertSee('Polpetone Filé Mignon')
        ->assertDontSee('борщ');
})->with([
    [DishesTable::class, 'price'],
    [DishesTableWithJoin::class, 'price'],
]);

it('ignores null "min & max"', function (string $component, string $field) {
    livewire($component)
    ->set('filters', filterNumber($field, null, null, '', ''))
    ->assertSee('Pastel de Nata')
    ->assertSee('Peixada da chef Nábia')
    ->assertSee('борщ');
})->with([
    [DishesTable::class, 'id'],
    [DishesTableWithJoin::class, 'dishes.id'],
]);

it('ignores null "min & max" - using collection')
    ->livewire(DishesCollectionTable::class)
    ->set('filters', filterNumber('price', null, null, '', ''))
    ->assertSee('Name 1')
    ->assertSee('Name 2')
    ->assertSee('Name 3')
    ->assertSee('Name 4')
    ->assertSee('Name 5');

it('displays "No records found" with non-existent min', function (string $component, string $field) {
    livewire($component)
    ->set('filters', filterNumber($field, '1000000', null, '', ''))
    ->assertSee('No records found')
    ->assertDontSee('Pastel de Nata');
})->with([
    [DishesTable::class, 'id'],
    [DishesTableWithJoin::class, 'dishes.id'],
]);

it('displays "No records found" with non-existent min - using collection')
    ->livewire(DishesCollectionTable::class)
    ->set('filters', filterNumber('price', '1000000', null, '', ''))
    ->assertSee('No records found')
    ->assertDontSee('Name 1');

it('properly filters by "min & max" formatted', function (string $component, string $field) {
    livewire($component)
        ->set('filters', filterNumber($field, '1,50', '20,51', '.', ','))
        ->assertSee('Pastel de Nata')
        ->assertSee('Peixada da chef Nábia')
        ->assertDontSee('Carne Louca');
})->with([
    [DishesTable::class, 'price'],
    [DishesTableWithJoin::class, 'price'],
]);

function filterNumber(string $field, ?string $min, ?string $max, ?string $thousands, ?string $decimal): array
{
    return [
        'number' => [
            $field => [
                'start'     => $min,
                'end'       => $max,
                'thousands' => $thousands,
                'decimal'   => $decimal,
            ],
        ],
    ];
}
