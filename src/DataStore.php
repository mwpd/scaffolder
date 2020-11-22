<?php
declare(strict_types=1);

namespace MWPD\Scaffolder;

use MWPD\Scaffolder\Exception\InvalidDataStoreKey;

final class DataStore
{

    /**
     * Associative array of data stored in the data store.
     *
     * @var array
     */
    private $data = [];

    /**
     * Instantiate a DataStore object.
     *
     * @param array $data Associative array of data to use as initial data store.
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * Check whether the data store has a given piece of data.
     *
     * @param string $key Key to check for.
     * @return bool Whether the data store has data for the requested key.
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->data);
    }

    /**
     * Set the value for a given key in the data store.
     *
     * @param string $key   Key to set the value for.
     * @param mixed  $value Value to set the key to.
     */
    public function set(string $key, $value): void
    {
        $this->data[$key] = $value;
    }

    /**
     * Get a specific piece of data from the data store based on the provided key.
     *
     * @param string $key Key to fetch the value for.
     * @return mixed Value for the requested key.
     * @throws InvalidDataStoreKey If the requested key does not exist.
     */
    public function get(string $key)
    {
        if (! $this->has($key)) {
            throw InvalidDataStoreKey::fromKey($key);
        }

        return $this->data[$key];
    }


    /**
     * Return the array representation of the data store object.
     *
     * @return array Associative array of data store information.
     */
    public function toArray()
    {
        return $this->data;
    }
}
