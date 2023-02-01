<?php

namespace AdventureTech\DataTransferObject\Tests\Unit;

use AdventureTech\DataTransferObject\Tests\Unit\TestDto\MapFromDto;

beforeEach(function () {
    $this->source = [
        'id' => 1,
        'first_name' => 'Ola',
        'last_name' => 'Nordmann',
        'email' => 'ola@example.com',
        'created_at' => '2022-09-19 12:00:00',
        'deleted_at' => null,
        'user_type' => 'admin',
    ];

    $this->dto = MapFromDto::from($this->source);
});

test('attribute MapFrom works', function () {
    $this->assertNotEmpty($this->dto->firstName);
    $this->assertEquals($this->dto->firstName, $this->source['first_name']);
    $this->assertEquals($this->dto->email, $this->source['email']);
});
