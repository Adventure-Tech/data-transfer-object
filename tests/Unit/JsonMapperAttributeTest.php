<?php

use AdventureTech\DataTransferObject\Tests\Unit\TestDto\JsonMapperDto;

beforeEach(function () {
    $this->dto = JsonMapperDto::from(createData());
});

test('JSON field address is mapped correctly', function () {
    expect($this->dto->firstName)->toBe('Tester')
        ->and($this->dto->address->street)->toBe('Testgata 11')
        ->and($this->dto->address->zip)->toBe('2317')
        ->and($this->dto->address->city)->toBe('Hamar');
});

test('Nested JSON field roleList is mapped correctly', function () {
    expect($this->dto->roleList->roles)->toHaveCount(2)
        ->and($this->dto->roleList->roles[0]->id)->toBe(1)
        ->and($this->dto->roleList->roles[0])->name->toBe('admin')
        ->and($this->dto->roleList->roles[1]->id)->toBe(2)
        ->and($this->dto->roleList->roles[1])->name->toBe('staff');
});

function createData()
{
    return [
        'first_name' => 'Tester',
        'address' => json_encode([
            'street' => 'Testgata 11',
            'zip' => '2317',
            'city' => 'Hamar'
        ]),
        'roleList' => json_encode([
            'roles' => [
                ['name' => 'admin', 'id' => 1],
                ['name' => 'staff', 'id' => 2],
            ]
        ]),
    ];
}