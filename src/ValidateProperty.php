<?php declare(strict_types=1);

namespace AdventureTech\DataTransferObject;

use AdventureTech\DataTransferObject\Exceptions\MissingPropertyTypeException;
use AdventureTech\DataTransferObject\Exceptions\PropertyAssignmentException;
use AdventureTech\DataTransferObject\Reflection\DataTransferObjectProperty;

final class ValidateProperty
{
    private function __construct()
    {
    }

    /**
     * @throws PropertyAssignmentException
     * @throws MissingPropertyTypeException
     */
    public static function validate(DataTransferObjectProperty $property): void
    {
        self::hasTypeDeclaration($property);
        self::hasCorrespondingSourceValue($property);
        self::requiredPropertyIsNotNull($property);
    }

    /**
     * @throws MissingPropertyTypeException
     */
    private static function hasTypeDeclaration(DataTransferObjectProperty $property): void
    {
        if (!$property->getType()) {
            throw new MissingPropertyTypeException($property);
        }
    }

    /**
     * @throws PropertyAssignmentException
     */
    private static function hasCorrespondingSourceValue(DataTransferObjectProperty $property): void
    {
        if (
            $property->isRequired() &&
            $property->propertyIsNotPresentOnSourceObject() &&
            !$property->hasDefaultValue()
        ) {
            throw new PropertyAssignmentException(
                "{$property->getDeclaringClassName()}'s property {$property->getName()} is non optional and must have corresponding property on the source"
            );
        }
    }

    /**
     * @throws PropertyAssignmentException
     */
    private static function requiredPropertyIsNotNull(DataTransferObjectProperty $property): void
    {
        if (
            !$property->allowsNull() &&
            $property->isRequired() &&
            $property->sourcePropertyIsNull() &&
            !$property->hasDefaultValue()
        ) {
            throw new PropertyAssignmentException(
                "{$property->getDeclaringClassName()}'s property {$property->getName()} does not allow null. Source property {$property->getSourcePropertyToMapFrom()} is null."
            );
        }
    }
}
