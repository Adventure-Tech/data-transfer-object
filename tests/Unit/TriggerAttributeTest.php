<?php

use AdventureTech\DataTransferObject\Tests\Unit\TestDto\TriggerDto;

test('Trigger methods is called as expected', function () {
    $data = [
        'number' => 100
    ];

    $dto = TriggerDto::from($data);
    expect($dto->triggerValue)->toBe(50);
});