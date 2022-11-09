<?php declare(strict_types=1);

namespace AdventureTech\DataTransferObject;

use AdventureTech\DataTransferObject\Exceptions\PropertyAssignmentException;
use AdventureTech\DataTransferObject\Exceptions\PropertyTypeException;
use AdventureTech\DataTransferObject\Reflection\DataTransferObjectProperty;

final class ValidateProperty
{
    private function __construct()
    {
    }

    /**
     * @throws PropertyAssignmentException
     * @throws PropertyTypeException
     */
    public static function validate(DataTransferObjectProperty $property): void
    {
        self::hasTypeDeclaration($property);
        self::hasCorrespondingSourceValue($property);
        self::requiredPropertyIsNotNull($property);
        self::typeDeclarationIsArrayWhenJsonAttribute($property);
    }

    /**
     * @throws PropertyTypeException
     */
    private static function hasTypeDeclaration(DataTransferObjectProperty $property): void
    {
        if (!$property->getType()) {
            throw new PropertyTypeException("{$property->getDeclaringClassName()}'s property {$property->getName()} is missing a type declaration");
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

    /**
     * @throws PropertyTypeException
     */
    private static function typeDeclarationIsArrayWhenJsonAttribute(DataTransferObjectProperty $property): void
    {
        if (
            $property->isFromJson()
            && is_null($property->castFromJsonToDto())
            && $property->getType() !== 'array'
        ) {
            throw new PropertyTypeException(
                'Attribute FromJson expects property to have type declaration array. 
                Change the property data type to array, or specify a DTO that the data should be cast to on the FromJson attribute.');
        }
    }
}
