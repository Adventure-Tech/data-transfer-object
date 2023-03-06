<?php declare(strict_types=1);

namespace AdventureTech\DataTransferObject;

use AdventureTech\DataTransferObject\Exceptions\PropertyAssignmentException;
use AdventureTech\DataTransferObject\Exceptions\PropertyTypeException;
use AdventureTech\DataTransferObject\Reflection\DataTransferObjectClass;
use AdventureTech\DataTransferObject\Reflection\DataTransferObjectProperty;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use stdClass;

abstract class DataTransferObject
{
    protected Collection $triggerMethodNames;

    /**
     * The only method that can be called publicly to create a new DTO.
     * Can optionally be overridden on children to ensure correct type hinting.
     *
     * @param  array|stdClass|Model  $source
     * @return DataTransferObject
     */
    public static function from(array|stdClass|Model $source): static
    {
        return self::fromMixed($source);
    }

    /**
     * Create new DTO from one of the supported types
     *
     * @param  array|stdClass|Model  $source
     * @return DataTransferObject
     */
    protected static function fromMixed(array|stdClass|Model $source): DataTransferObject
    {
        return match (SourceType::getSourceType($source)) {
            SourceType::ELOQUENT => self::fromEloquent($source),
            SourceType::STDCLASS => self::fromStdClass($source),
            SourceType::ARRAY => self::fromArray($source),
        };
    }

    /**
     * Create new DTO from Eloquent model
     *
     * @param  Model  $model
     * @return DataTransferObject
     */
    private static function fromEloquent(Model $model): DataTransferObject
    {
        return self::fromStdClass((object) $model->attributesToArray());
    }

    /**
     * Create new DTO from array
     *
     * @param  array  $values
     * @return DataTransferObject
     */
    private static function fromArray(array $values): DataTransferObject
    {
        return self::fromStdClass((object) $values);
    }

    /**
     * Create new DTO from stdClass
     *
     * @param  stdClass  $source
     * @return DataTransferObject
     */
    private static function fromStdClass(stdClass $source): DataTransferObject
    {
        $calledClassName = get_called_class();
        $newDto = new $calledClassName($source);
        $newDto->callTriggerMethodsAfterConstruction();
        return $newDto;
    }

    private function setPropertyValue(DataTransferObjectProperty $property, mixed $propertyValue): void
    {
        if ($property->valueAssignmentCanBeSkipped()) {
            return;
        }
        $this->{$property->getName()} = $propertyValue;
    }

    protected function callTriggerMethodsAfterConstruction(): void
    {
        $this->triggerMethodNames->each(function (string $methodName) {
            $this->{$methodName}();
        });
    }

    /**
     * @throws PropertyAssignmentException
     * @throws PropertyTypeException
     */
    private function __construct(stdClass $source)
    {
        $reflectionClass = new DataTransferObjectClass($this, $source);

        // Map all values from the source to the DataTransferObject
        $reflectionClass->getProperties()->each(function (DataTransferObjectProperty $property) use ($source) {
            $this->setPropertyValue($property, $property->getSourceValue());
        });

        $this->triggerMethodNames = $reflectionClass->getTriggerMethodNames();
    }

}