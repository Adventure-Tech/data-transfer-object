<?php

namespace AdventureTech\DataTransferObject\Tests\Unit\Units;

use AdventureTech\DataTransferObject\Attributes\DefaultValue;
use AdventureTech\DataTransferObject\Attributes\FromJson;
use AdventureTech\DataTransferObject\Attributes\MapFrom;
use AdventureTech\DataTransferObject\Attributes\Optional;
use AdventureTech\DataTransferObject\DataTransferObject;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use stdClass;

class UserDTO extends DataTransferObject
{
    public int $id;
    #[MapFrom('first_name')]
    public string $firstName;
    #[MapFrom('last_name')]
    public string $lastName;
    public string $email;
    #[FromJson]
    public array $address;
    #[MapFrom('phone_numbers')]
    #[FromJson(PhoneNumbersDTO::class)]
    public PhoneNumbersDTO $phoneNumbers;
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

    // Optional override of parent::from() with the sole purpose of providing correct type hints.
    public static function from(Model|array|stdClass $source): UserDTO
    {
        return parent::from($source);
    }
}
