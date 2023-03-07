<?php

namespace AdventureTech\DataTransferObject\JsonMapper;

use AdventureTech\DataTransferObject\Exceptions\PropertyAssignmentException;
use Exception;
use JsonMapper;

class MapFromJsonToDto
{
    private string $rootClassName;
    private string $jsonDataString;
    private JsonMapper $mapper;

    public function __construct(string $rootClassName, string $jsonDataString)
    {
        $this->rootClassName = $rootClassName;
        $this->jsonDataString = $jsonDataString;
        $this->mapper = new JsonMapper();
        $this->mapper->bEnforceMapType = false;
    }

    /**
     * @throws PropertyAssignmentException
     */
    public function mapJsonAndReturnRootObject(): mixed
    {
        try {
            return $this->mapper->map(
                json_decode($this->jsonDataString),
                new $this->rootClassName(),
            );
        } catch (Exception $ex) {
            throw new PropertyAssignmentException($ex->getMessage());
        }
    }

    public static function map(string $rootClassName, string $jsonDataString): mixed
    {
        $self = new self($rootClassName, $jsonDataString);
        return $self->mapJsonAndReturnRootObject();
    }
}