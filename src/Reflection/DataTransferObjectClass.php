<?php declare(strict_types=1);

namespace AdventureTech\DataTransferObject\Reflection;

use AdventureTech\DataTransferObject\DataTransferObject;
use AdventureTech\DataTransferObject\Exceptions\MissingPropertyTypeException;
use AdventureTech\DataTransferObject\Exceptions\PropertyAssignmentException;
use Illuminate\Support\Collection;
use ReflectionClass;
use ReflectionProperty;
use stdClass;

final class DataTransferObjectClass
{
    private ReflectionClass $reflection;
    private stdClass $source;

    public function __construct(DataTransferObject $dataTransferObject, stdClass $source)
    {
        $this->reflection = new ReflectionClass($dataTransferObject);
        $this->source = $source;
    }

    /**
     * Get all public properties declared on the DataTransferObject
     * @throws MissingPropertyTypeException
     * @throws PropertyAssignmentException
     */
    public function getProperties(): Collection
    {
        $dataTransferObjectProperties = Collection::empty();
        $reflectionProperties = $this->reflection->getProperties(ReflectionProperty::IS_PUBLIC);

        foreach ($reflectionProperties as $prop) {
            $dataTransferObjectProperties->add(new DataTransferObjectProperty($prop, $this->source));
        }

        return $dataTransferObjectProperties;
    }

}
