<?php declare(strict_types=1);

namespace AdventureTech\DataTransferObject\Exceptions;

use AdventureTech\DataTransferObject\Reflection\DataTransferObjectProperty;
use Exception;

class MissingPropertyTypeException extends Exception
{
    public function __construct(DataTransferObjectProperty $property)
    {
        parent::__construct(
            "{$property->getDeclaringClassName()}'s property {$property->getName()} is missing a type declaration"
        );
    }
}
