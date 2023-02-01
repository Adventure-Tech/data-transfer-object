<?php

use AdventureTech\DataTransferObject\Tests\Unit\TestDto\OptionalDto;

beforeEach(function () {
    $this->dto = OptionalDto::from([]);
});

test('Optional property is not instantiated', function () {
    expect(isset($this->dto->iAmOptional))->toBeFalse();
});
