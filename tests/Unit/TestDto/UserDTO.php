<?php

namespace AdventureTech\DataTransferObject\Tests\Unit\TestDto;

use AdventureTech\DataTransferObject\Attributes\DefaultValue;
use AdventureTech\DataTransferObject\Attributes\MapFrom;
use AdventureTech\DataTransferObject\Attributes\Optional;
use AdventureTech\DataTransferObject\DataTransferObject;
use Carbon\Carbon;

class UserDTO extends DataTransferObject
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
    #[Optional]
    public string $iAmOptional;
    // Relations
    #[DefaultValue([])]
    public array $posts;
}
