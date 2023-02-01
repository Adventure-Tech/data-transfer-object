<?php

namespace AdventureTech\DataTransferObject\Tests\Unit\TestDto;

use AdventureTech\DataTransferObject\Attributes\MapFrom;
use AdventureTech\DataTransferObject\DataTransferObject;
use Carbon\Carbon;

class MapFromDto extends DataTransferObject
{
    public int $id;
    #[MapFrom('first_name')]
    public string $firstName;
    #[MapFrom('last_name')]
    public string $lastName;
    public string $email;
    #[MapFrom('user_type')]
    public UserTypeEnum $userType;
    #[MapFrom('created_at')]
    public Carbon $createdAt;
    #[MapFrom('deleted_at')]
    public ?Carbon $deletedAt;
}