<?php

use AdventureTech\DataTransferObject\Tests\Unit\TestDto\DefaultValueDto;

beforeEach(function () {
    $this->dto = DefaultValueDto::from([]);
});

test('attribute DefaultValue works', function () {
    expect(is_array($this->dto->posts))->toBeTrue();
});
