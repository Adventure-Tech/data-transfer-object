<?php declare(strict_types=1);

namespace AdventureTech\DataTransferObject\Reflection;

use AdventureTech\DataTransferObject\Attributes\DefaultValue;
use AdventureTech\DataTransferObject\Attributes\JsonMapper;
use AdventureTech\DataTransferObject\Attributes\MapFrom;
use AdventureTech\DataTransferObject\Attributes\Optional;
use AdventureTech\DataTransferObject\Attributes\Trigger;
use AdventureTech\DataTransferObject\Exceptions\PropertyAssignmentException;
use AdventureTech\DataTransferObject\Exceptions\PropertyValidationException;
use AdventureTech\DataTransferObject\JsonMapper\MapFromJsonToDto;
use AdventureTech\DataTransferObject\ValidateProperty;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use ReflectionAttribute;
use ReflectionProperty;
use stdClass;

final class DataTransferObjectProperty
{
    private ReflectionProperty $reflection;
    private Collection $attributes;
    private stdClass $source;

    /**
     * @throws PropertyValidationException
     */
    public function __construct(ReflectionProperty $reflectionProperty, stdClass $source)
    {
        $this->reflection = $reflectionProperty;
        $this->attributes = collect($this->reflection->getAttributes());
        $this->source = $source;
        ValidateProperty::validate($this);
    }

    /**
     * Get the name of the property
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->reflection->getName();
    }

    /**
     * Get the declared type of the property
     *
     * @return ?string
     */
    public function getType(): ?string
    {
        return $this->reflection->getType()?->getName() ?: null;
    }

    /**
     * Get the name of the class this property belongs to
     *
     * @return string
     */
    public function getDeclaringClassName(): string
    {
        return $this->reflection->getDeclaringClass()->getName();
    }

    /**
     * Fetch a specific attribute if attached to property
     *
     * @param  string  $attributeName
     * @return ReflectionAttribute|null
     */
    private function getAttribute(string $attributeName): ?ReflectionAttribute
    {
        foreach ($this->attributes as $attribute) {
            if ($attribute->getName() == $attributeName) {
                return $attribute;
            }
        }
        return null;
    }

    /**
     * Check if a specific attribute is attached to the property
     *
     * @param  string  $attributeName
     * @return bool
     */
    private function hasAttribute(string $attributeName): bool
    {
        return (bool) $this->getAttribute($attributeName);
    }

    /**
     * Determine which source property to map value from
     *
     * @return string
     */
    public function getSourcePropertyToMapFrom(): string
    {
        $mapFrom = $this->getAttribute(MapFrom::class);
        return $mapFrom ? $mapFrom->getArguments()[0] : $this->reflection->getName();
    }

    /**
     * Determine if the property has the Optional attribute attached
     *
     * @return bool
     */
    private function isOptional(): bool
    {
        return $this->hasAttribute(Optional::class);
    }

    /**
     * Determine if the property type is an enum
     *
     * @return bool
     */
    private function isEnum(): bool
    {
        return enum_exists($this->reflection->getType()->getName());
    }

    /**
     * Alias for the isOptional() method
     *
     * @return bool
     */
    public function isRequired(): bool
    {
        return false == $this->isOptional();
    }

    /**
     * Determine if the property is declared nullable
     *
     * @return bool
     */
    public function allowsNull(): bool
    {
        return $this->reflection->getType()->allowsNull();
    }

    /**
     * Check if the DefaultValue attribute is attached to the property
     *
     * @return bool
     */
    public function hasDefaultValue(): bool
    {
        return $this->hasAttribute(DefaultValue::class);
    }

    /**
     * Determine if property has the JsonMapper attribute attached
     *
     * @return bool
     */
    private function useJsonMapper(): bool
    {
        return $this->hasAttribute(JsonMapper::class);
    }

    /**
     * Determine if property has the Trigger attribute attached
     *
     * @return bool
     */
    public function hasTrigger(): bool
    {
        return $this->hasAttribute(Trigger::class);
    }

    /**
     * Return the method name provided the the Trigger attribute
     *
     * @return string
     */
    public function getTriggerMethodName(): string
    {
        $triggerAttribute = $this->getAttribute(Trigger::class);

        return $triggerAttribute->getArguments()[0];
    }

    /**
     * Return the name of the root class name that the json data should be mapped to.
     * The root class may contain nested classes corresponding with the JSON structure.
     *
     * @return string
     * @throws PropertyAssignmentException
     */
    private function getRootClassNameForJsonMapper(): string
    {
        if (!$this->useJsonMapper()) {
            throw new PropertyAssignmentException(
                "Trying to get a root class name from attribute JsonMapper, 
               but no JsonMapper attribute was specified on this property"
            );
        }

        $fromJsonAttribute = $this->getAttribute(JsonMapper::class);

        return $fromJsonAttribute->getArguments()[0];
    }

    /**
     * Determine if the source property being mapped from is actually present on the source object
     *
     * @return bool
     */
    public function propertyIsNotPresentOnSourceObject(): bool
    {
        return false == property_exists($this->source, $this->getSourcePropertyToMapFrom());
    }

    /**
     * Check if the source property being mapped from is null
     * Assumed the property is present on the source object
     *
     * @return bool
     */
    public function sourcePropertyIsNull(): bool
    {
        if (property_exists($this->source, $this->getSourcePropertyToMapFrom())) {
            return is_null($this->source->{$this->getSourcePropertyToMapFrom()});
        }
        return true;
    }

    /**
     * Check if property has a type declaration of Carbon
     *
     * @return bool
     */
    private function isCarbon(): bool
    {
        return $this->reflection->getType()->getName() === 'Carbon\Carbon';
    }

    /**
     * Check property has a type declaration of bool
     *
     * @return bool
     */
    private function isBoolean(): bool
    {
        return $this->reflection->getType()->getName() === 'bool';
    }

    /**
     * Fetch the default value of this property if the DefaultValue attribute is attached.
     *
     * @return mixed
     * @throws PropertyAssignmentException
     */
    private function getDefaultValue(): mixed
    {
        $defaultValueAttribute = $this->getAttribute(DefaultValue::class);

        $defaultValue = $defaultValueAttribute ? $defaultValueAttribute->getArguments()[0] : null;

        return $this->castValue($defaultValue);
    }


    /**
     * Inspects the type declaration of this property and casts the value if necessary
     *
     * @param  mixed  $value
     * @return mixed
     * @throws PropertyAssignmentException
     */
    private function castValue(mixed $value): mixed
    {
        if (is_null($value)) {
            return null;
        }
        if ($this->isCarbon()) {
            return Carbon::parse($value);
        }
        if ($this->isBoolean()) {
            return (bool) $value;
        }
        if ($this->useJsonMapper()) {
            return MapFromJsonToDto::map($this->getRootClassNameForJsonMapper(), $value);
        }
        if ($this->isEnum()) {
            return $this->reflection->getType()->getName()::from($value);
        }
        return $value;
    }

    /**
     * Get the value mapped from the source object
     *
     * @return mixed
     * @throws PropertyAssignmentException
     */
    private function getSourcePropertyValue(): mixed
    {
        if (property_exists($this->source, $this->getSourcePropertyToMapFrom())) {
            return $this->castValue($this->source->{$this->getSourcePropertyToMapFrom()});
        }
        return null;
    }

    /**
     * Map and return the value from the source object
     *
     * @return mixed
     * @throws PropertyAssignmentException
     */
    public function getSourceValue(): mixed
    {
        return !is_null($this->getSourcePropertyValue()) ? $this->getSourcePropertyValue() : $this->getDefaultValue();
    }

    /**
     * Determine if the property must be assigned a value or not
     *
     * @return bool
     */
    public function valueAssignmentCanBeSkipped(): bool
    {
        // Can be skipped if optional
        if ($this->propertyIsNotPresentOnSourceObject() && $this->isOptional() && !$this->hasDefaultValue()) {
            return true;
        }
        // Can be skipped if trigger is attached
        if ($this->hasTrigger()) {
            return true;
        }

        return false;
    }
}
