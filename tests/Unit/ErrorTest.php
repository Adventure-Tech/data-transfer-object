<?php

use AdventureTech\DataTransferObject\Exceptions\PropertyValidationException;
use AdventureTech\DataTransferObject\Tests\Unit\TestDto\FooDTO;
use AdventureTech\DataTransferObject\Tests\Unit\TestDto\UserDTO;
use AdventureTech\DataTransferObject\Tests\Unit\TestDto\UserDTOMissingType;

test('Error when property is not mapped correctly', function () {
    $this->source = [
        'id' => 1,
        'first_nname' => 'Ola',
        'last_name' => 'Nordmann',
        'email' => 'ola@example.com',
        'created_at' => '2022-09-19 12:00:00',
        'deleted_at' => null,
    ];

    $this->dto = UserDTO::from($this->source);
})->throws(
    PropertyValidationException::class,
    "UserDTO's property firstName is non optional and must have corresponding property on the source"
);


test('Error when property is missing type declaration', function () {
    $this->source = [
        'id' => 1,
    ];

    $this->dto = UserDTOMissingType::from($this->source);
})->throws(PropertyValidationException::class);


test('Error when source is wrong type', function () {
    UserDTO::from('foo');
})->throws(TypeError::class);


test('Error when a field is non optional, but source value is null', function () {
    $source = [
        'value1' => 'foo',
        'value2' => null,
    ];

    FooDTO::from($source);
})->throws(
    PropertyValidationException::class,
    FooDTO::class."'s property value2 does not allow null. Source property value2 is null."
);