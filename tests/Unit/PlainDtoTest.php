<?php

use AdventureTech\DataTransferObject\Tests\Unit\TestDto\PlainDto;
use AdventureTech\DataTransferObject\Tests\Unit\TestDto\UserTypeEnum;
use Carbon\Carbon;

beforeEach(function () {
    $this->source = [
        'created_at' => '2022-09-19 12:00:00',
        'deleted_at' => null,
        'user_type' => 'admin',
    ];

    $this->dto = PlainDto::from($this->source);
});

test('nullable properties work', function () {
    expect($this->dto->deletedAt)->toBeNull();
});

test('Property type of carbon works', function () {
    expect($this->dto->createdAt instanceof Carbon)->toBeTrue();
});

test('value can be cast to enum (backed enum)', function () {
    expect($this->dto->userType)->toBe(UserTypeEnum::ADMIN);
});
