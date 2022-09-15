<?php declare(strict_types=1);

namespace AdventureTech\DataTransferObject;

use Illuminate\Database\Eloquent\Model;
use stdClass;
use InvalidArgumentException;

enum SourceType: string
{
    case ELOQUENT = 'eloquent';
    case STDCLASS = 'stdClass';
    case ARRAY = 'array';

    public static function getSourceType(array|stdClass|Model $source): SourceType
    {
        if (is_a($source, Model::class)) {
            return self::ELOQUENT;
        }
        if (is_a($source, 'stdClass')) {
            return self::STDCLASS;
        }
        if (is_array($source)) {
            return self::ARRAY;
        }
        throw new InvalidArgumentException('Invalid source type for DataTransferObject');
    }
}
