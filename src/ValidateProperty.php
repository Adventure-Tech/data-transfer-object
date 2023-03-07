<?php declare(strict_types=1);

namespace AdventureTech\DataTransferObject;

use AdventureTech\DataTransferObject\Exceptions\PropertyValidationException;
use AdventureTech\DataTransferObject\Reflection\DataTransferObjectProperty;

final class ValidateProperty
{
    private function __construct()
    {
    }

    /**
     * @throws PropertyValidationException
     */
    public static function validate(DataTransferObjectProperty $property): void
    {
        self::hasTypeDeclaration($property);
        self::hasCorrespondingSourceValue($property);
        self::requiredPropertyIsNotNull($property);
        self::triggerMethodMustBePresentOnDto($property);
        self::triggerMethodCanNotBePrivate($property);
    }

    /**
     * @throws PropertyValidationException
     */
    private static function hasTypeDeclaration(DataTransferObjectProperty $property): void
    {
        if (!$property->getType()) {
            throw new PropertyValidationException("{$property->getDeclaringClassName()}'s property {$property->getName()} is missing a type declaration");
        }
    }

    /**
     * @throws PropertyValidationException
     */
    private static function hasCorrespondingSourceValue(DataTransferObjectProperty $property): void
    {
        if (
            $property->isRequired() &&
            $property->propertyIsNotPresentOnSourceObject() &&
            !$property->hasDefaultValue() &&
            !$property->hasTrigger()
        ) {
            throw new PropertyValidationException(
                "{$property->getDeclaringClassName()}'s property {$property->getName()} is non optional and must have corresponding property on the source"
            );
        }
    }

    /**
     * @throws PropertyValidationException
     */
    private static function requiredPropertyIsNotNull(DataTransferObjectProperty $property): void
    {
        if (
            !$property->allowsNull() &&
            $property->isRequired() &&
            $property->sourcePropertyIsNull() &&
            !$property->hasDefaultValue() &&
            !$property->hasTrigger()
        ) {
            throw new PropertyValidationException(
                "{$property->getDeclaringClassName()}'s property {$property->getName()} does not allow null. Source property {$property->getSourcePropertyToMapFrom()} is null."
            );
        }
    }

    /**
     * @throws PropertyValidationException
     */
    private static function triggerMethodMustBePresentOnDto(DataTransferObjectProperty $property)
    {
        if ($property->hasTrigger()) {
            try {
                $methodNameToValidate = $property->getTriggerMethodName();
                $dtoReflection = new \ReflectionClass($property->getDeclaringClassName());
                $dtoReflection->getMethod($methodNameToValidate);
            } catch (\ReflectionException $ex) {
                throw new PropertyValidationException(
                    "{$property->getDeclaringClassName()}'s property {$property->getName()} is registered with 
                    a trigger attribute that expects the method name $methodNameToValidate to exist on this DTO"
                );
            }

        }
    }

    // Trigger method must be public or protected
    private static function triggerMethodCanNotBePrivate(DataTransferObjectProperty $property)
    {
        if ($property->hasTrigger()) {
            $methodNameToValidate = $property->getTriggerMethodName();
            $dtoReflection = new \ReflectionClass($property->getDeclaringClassName());
            $reflectionMethod = $dtoReflection->getMethod($methodNameToValidate);
            if ($reflectionMethod->isPrivate()) {
                throw new PropertyValidationException(
                    "Trigger methods can must be public of protected"
                );
            }
        }
    }
}