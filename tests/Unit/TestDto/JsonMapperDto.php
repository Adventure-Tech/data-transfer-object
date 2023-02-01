<?php

namespace AdventureTech\DataTransferObject\Tests\Unit\TestDto;

use AdventureTech\DataTransferObject\Attributes\JsonMapper;
use AdventureTech\DataTransferObject\Attributes\MapFrom;
use AdventureTech\DataTransferObject\DataTransferObject;

class JsonMapperDto extends DataTransferObject
{
    #[MapFrom('first_name')]
    public string $firstName;

    #[JsonMapper(Address::class)]
    public Address $address;

    #[JsonMapper(RoleList::class)]
    public RoleList $roleList;
}