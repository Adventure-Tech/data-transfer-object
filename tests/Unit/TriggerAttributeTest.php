<?php

use AdventureTech\DataTransferObject\Exceptions\PropertyValidationException;
use AdventureTech\DataTransferObject\Tests\Unit\TestDto\TriggerDto;
use AdventureTech\DataTransferObject\Tests\Unit\TestDto\TriggerMissingMethodDto;
use AdventureTech\DataTransferObject\Tests\Unit\TestDto\TriggerVisibilityErrorDto;

test('Trigger methods is called as expected', function () {
    $data = [
        'number' => 100
    ];

    $dto = TriggerDto::from($data);
    expect($dto->triggerValue)->toBe(50);
});

test('Correct exception is thrown when trigger method is not implemented', function () {
    $data = [
        'number' => 100
    ];

    TriggerMissingMethodDto::from($data);
})->throws(
    PropertyValidationException::class,
    "TriggerMissingMethodDto's property calculatedNumber is registered with 
                    a trigger attribute that expects the method name calculateNumber to exist on this DTO"
);

test('Correct exception is thrown when trigger method is private', function () {
    $data = [
        'number' => 100
    ];

    TriggerVisibilityErrorDto::from($data);
})->throws(
    PropertyValidationException::class,
    "Trigger methods can must be public of protected"
);
