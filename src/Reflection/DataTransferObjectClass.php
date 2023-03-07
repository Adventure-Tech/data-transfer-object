<?php declare(strict_types=1);

namespace AdventureTech\DataTransferObject\Reflection;

use AdventureTech\DataTransferObject\DataTransferObject;
use AdventureTech\DataTransferObject\Exceptions\PropertyValidationException;
use Illuminate\Support\Collection;
use ReflectionClass;
use ReflectionProperty;
use stdClass;

final class DataTransferObjectClass
{
    private ReflectionClass $reflection;
    private Collection $dtoProperties;
    private stdClass $source;

    /**
     * @throws PropertyValidationException
     */
    public function __construct(DataTransferObject $dataTransferObject, stdClass $source)
    {
        $this->reflection = new ReflectionClass($dataTransferObject);
        $this->dtoProperties = Collection::empty();
        $this->source = $source;
        $this->setDataTransferObjectReflectionProperties();
    }

    /**
     * Traverse all DTO properties and create a reflection property
     *
     * @throws PropertyValidationException
     */
    private function setDataTransferObjectReflectionProperties(): void
    {
        foreach ($this->reflection->getProperties(ReflectionProperty::IS_PUBLIC) as $reflectionProperty) {
            $this->dtoProperties->add(
                new DataTransferObjectProperty($reflectionProperty, $this->source)
            );
        }
    }

    /**
     * Return all reflection properties to this DTO
     *
     * @return Collection
     */
    public function getProperties(): Collection
    {
        return $this->dtoProperties;
    }

    /**
     * Get all the defined trigger method names for this DTO
     *
     * @return Collection
     */
    public function getTriggerMethodNames(): Collection
    {
        $methodNames = Collection::empty();

        $this->dtoProperties->each(function (DataTransferObjectProperty $property) use ($methodNames) {
            if ($property->hasTrigger()) {
                $methodNames->add($property->getTriggerMethodName());
            }
        });

        return $methodNames;
    }

}
