<?php

declare(strict_types=1);

namespace MWPD\Scaffolder;

use MWPD\Scaffolder\Exception\InvalidConfigurationKey;

final class Configuration
{

    /**
     * Associative array of data stored in the configuration.
     *
     * @var array
     */
    private $data = [];

    /**
     * Instantiate a Configuration object.
     *
     * @param array $data Associative array of data to use as initial configuration.
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * Check whether the configuration has a given piece of data.
     *
     * @param string $key Key to check for.
     * @return bool Whether the configuration has data for the requested key.
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->data);
    }

    /**
     * Set the value for a given key in the configuration.
     *
     * @param string $key   Key to set the value for.
     * @param mixed  $value Value to set the key to.
     */
    public function set(string $key, $value): void
    {
        $this->data[$key] = $value;
    }

    /**
     * Get a specific piece of data from the configuration based on the provided key.
     *
     * @param string $key Key to fetch the value for.
     * @return mixed Value for the requested key.
     * @throws InvalidConfigurationKey If the requested key does not exist.
     */
    public function get(string $key)
    {
        if (! $this->has($key)) {
            throw InvalidConfigurationKey::fromKey($key);
        }

        return $this->data[$key];
    }

    /**
     * Return the array representation of the configuration object.
     *
     * @return array Associative array of configuration information.
     */
    public function toArray(): array
    {
        return $this->data;
    }
}
