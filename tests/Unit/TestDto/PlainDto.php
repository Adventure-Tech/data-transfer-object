<?php

namespace AdventureTech\DataTransferObject\Tests\Unit\TestDto;

use AdventureTech\DataTransferObject\Attributes\MapFrom;
use AdventureTech\DataTransferObject\DataTransferObject;
use Carbon\Carbon;

class PlainDto extends DataTransferObject
{
    #[MapFrom('user_type')]
    public UserTypeEnum $userType;

    #[MapFrom('created_at')]
    public Carbon $createdAt;

    #[MapFrom('deleted_at')]
    public ?Carbon $deletedAt;
}