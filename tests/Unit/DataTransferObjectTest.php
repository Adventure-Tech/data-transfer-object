<?php

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
        'user_type' => 'admin',
    ];

    $this->dto = UserDTO::from($this->source);
});

test('firstName property was mapped from first_name', function () {

    $this->assertNotEmpty($this->dto->firstName);
    $this->assertEquals($this->dto->firstName, $this->source['first_name']);
    $this->assertEquals($this->dto->email, $this->source['email']);
});

test('deletedAt property is null', function () {
    expect($this->dto->deletedAt)->toBeNull();
});

test('optional property is not instantiated', function () {
    expect(isset($this->dto->iAmOptional))->toBeFalse();
});

test('createdAt property is carbon instance', function () {
    expect($this->dto->createdAt instanceof Carbon)->toBeTrue();
});

test('posts property has default value', function () {
    expect(is_array($this->dto->posts))->toBeTrue();
});

test('address property is decoded from json', function () {
    expect($this->dto->address)->toBeArray();
    $this->assertEquals('Example 1', $this->dto->address['address1']);
    $this->assertEquals('Example 2', $this->dto->address['address2']);
});

test('phone numbers was decoded from json to dto', function () {
    expect($this->dto->phoneNumbers->primary)->toBe('47004700')
        ->and($this->dto->phoneNumbers->secondary)->toBe('47114711');
});

test('value can be cast to enum (backed enum)', function () {
    expect($this->dto->userType)->toBe(UserTypeEnum::ADMIN);
});
