<?php

namespace App\Entities;

use App\Exceptions\InvalidEntityKeyException;
use App\Exceptions\InvalidJsonException;

/**
 * Extend this class with Entity classes that define the entity
 * properties, types and optional default values. This creates
 * a single named object that can pass data between classes
 * whilst ensuring type safety and any required validation.
 */
abstract class Entity
{
    /**
     * By default ignore any property values that don't exist.
     * Override this to 'true' to throw an exception instead.
     */
    protected bool $throwExceptionOnUndefinedKey = false;

    private array $keyCache;

    /**
     * Optionally set data on construct in array or json format
     * 'Collection' could be added to the allowed types in a laravel implementation
     */
    public function __construct(array|string|null $data = null) {
        if ($data) {
            $this->set($data);
        }
    }

    protected function validate($key, $value): void
    {
        // Override this class - perform any desired validation checks
        // on input values and throw an exception if invalid
    }

    /**
     * Get raw property values
     */
    public function get(): array
    {
        return get_object_vars($this);
    }

    /**
     * Convert keys to original format
     */
    public function toArray(): array
    {
        $array = [];
        foreach ($this->get() as $key => $value) {
            $array[$this->keyCache[$key]] = $value;
        }

        return $array;
    }

    /**
     * Convert keys to original format and return as json
     */
    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    /**
     * Set individual properties via magic method
     */
    public function __set($key, $value)
    {
        $this->set([$key => $value]);
    }

    /**
     * Set data on construct in array or json format
     * 'Collection' could be added to the allowed
     * types in a laravel implementation
     */
    public function set(array|string $data): self
    {
        if (is_string($data)) {
            $data = json_decode($data, JSON_OBJECT_AS_ARRAY);

            //In Laravel use 'throw_if'
            if (is_null($data)) {
                throw new InvalidJsonException("The supplied JSON data was invalid");
            }
        }

        foreach ($data as $rawKey => $value) {
            $key = $this->camel($rawKey);
            $this->keyCache[$key] = $rawKey;

            if (!property_exists($this, $key)) {
                if ($this->throwExceptionOnUndefinedKey) {
                    $entityName =  (new \ReflectionClass($this))->getShortName();
                    throw new InvalidEntityKeyException("Invalid $entityName entity key $key");
                }

                continue;
            }

            $this->validate($key, $value);

            $this->$key = $value;
        }

        return $this;
    }

    /**
     * If keys are passed in as e.g. snake or kebab case, convert
     * to camel-case to follow property naming conventions
     */
    private function camel($value)
    {
        $words = preg_split('/\s-_/', $value);
        $upperCaseWords = array_map(fn ($word) => ucfirst($word), $words);

        return lcfirst(implode($upperCaseWords));
    }
}