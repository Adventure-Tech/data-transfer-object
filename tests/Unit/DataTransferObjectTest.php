<?php

use AdventureTech\DataTransferObject\Tests\Unit\Units\UserDTO;
use Carbon\Carbon;

beforeEach(function () {
    $this->source = [
        'id' => 1,
        'first_name' => 'Ola',
        'last_name' => 'Nordmann',
        'email' => 'ola@example.com',
        'created_at' => '2022-09-19 12:00:00',
        'deleted_at' => null,
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
