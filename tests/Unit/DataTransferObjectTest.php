<?php

use AdventureTech\DataTransferObject\Tests\Unit\Units\PriceDTO;
use AdventureTech\DataTransferObject\Tests\Unit\Units\UserDTO;
use AdventureTech\DataTransferObject\Tests\Unit\Units\UserTypeEnum;
use Carbon\Carbon;

beforeEach(function () {
    $this->source = [
        'id' => 1,
        'first_name' => 'Ola',
        'last_name' => 'Nordmann',
        'email' => 'ola@example.com',
        'created_at' => '2022-09-19 12:00:00',
        'deleted_at' => null,
        'address' => '{"address1": "Example 1", "address2": "Example 2"}',
        'phone_numbers' => '{"primary" : "47004700", "secondary" : "47114711"}',
        'prices' => '[{"price": 100, "currency": "NOK"}, {"price": 500, "currency": "NOK"}]',
        'user_type' => 'admin',
    ];

    $this->dto = UserDTO::from($this->source);
});

test('attribute MapFrom works', function () {

    $this->assertNotEmpty($this->dto->firstName);
    $this->assertEquals($this->dto->firstName, $this->source['first_name']);
    $this->assertEquals($this->dto->email, $this->source['email']);
});

test('nullable properties work', function () {
    expect($this->dto->deletedAt)->toBeNull();
});

test('optional property is not instantiated', function () {
    expect(isset($this->dto->iAmOptional))->toBeFalse();
});

test('Property type of carbon works', function () {
    expect($this->dto->createdAt instanceof Carbon)->toBeTrue();
});

test('attribute DefaultValue works', function () {
    expect(is_array($this->dto->posts))->toBeTrue();
});

test('can convert json field to simple associative array', function () {
    expect($this->dto->address)->toBeArray();
    $this->assertEquals('Example 1', $this->dto->address['address1']);
    $this->assertEquals('Example 2', $this->dto->address['address2']);
});

test('can convert a json field with a singular object to a dto', function () {
    expect($this->dto->phoneNumbers->primary)->toBe('47004700')
        ->and($this->dto->phoneNumbers->secondary)->toBe('47114711');
});

test('can convert a json field with array root to list of dtos', function () {
    expect($this->dto->prices)->toBeArray()
        ->and(get_class($this->dto->prices[0]))->toBe(PriceDTO::class)
        ->and($this->dto->prices[0]->price)->toBe(100);
});

test('value can be cast to enum (backed enum)', function () {
    expect($this->dto->userType)->toBe(UserTypeEnum::ADMIN);
});
