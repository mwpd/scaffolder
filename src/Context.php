<?php

declare(strict_types=1);

namespace MWPD\Scaffolder;

use MWPD\Scaffolder\Exception\InvalidContextKey;

final class Context
{

    public const FILEPATH = '_internal_filepath';
    public const TYPE     = '_internal_type';

    /**
     * Associative array of data stored in the context.
     *
     * @var array
     */
    private $data = [];

    /**
     * Instantiate a Context object.
     *
     * @param array $data Associative array of data to use as initial context.
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * Check whether the context has a given piece of data.
     *
     * @param string $key Key to check for.
     * @return bool Whether the context has data for the requested key.
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->data);
    }

    /**
     * Set the value for a given key in the context.
     *
     * @param string $key   Key to set the value for.
     * @param mixed  $value Value to set the key to.
     */
    public function set(string $key, $value): void
    {
        $this->data[$key] = $value;
    }

    /**
     * Get a specific piece of data from the context based on the provided key.
     *
     * @param string $key Key to fetch the value for.
     * @return mixed Value for the requested key.
     * @throws InvalidContextKey If the requested key does not exist.
     */
    public function get(string $key)
    {
        if (! $this->has($key)) {
            throw InvalidContextKey::fromKey($key);
        }

        return $this->data[$key];
    }

    /**
     * Return the array representation of the context object.
     *
     * @param bool $withInternals Optional. Whether to include the internal keys. Defaults to false.
     * @return array Associative array of context information.
     */
    public function toArray(bool $withInternals = false)
    {
        $context = $this->data;

        if (! $withInternals) {
            foreach ([self::FILEPATH, self::TYPE] as $internalKey) {
                unset($internalKey);
            }
        }

        return $context;
    }
}
