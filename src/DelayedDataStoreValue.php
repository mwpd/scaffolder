<?php
declare(strict_types=1);

namespace MWPD\Scaffolder;

final class DelayedDataStoreValue
{

    /**
     * Data store to use.
     *
     * @var DataStore
     */
    private $dataStore;

    /**
     * Key to retrieve.
     *
     * @var string
     */
    private $key;

    /**
     * Instantiate a DelayedDataStoreValue object.
     *
     * @param DataStore $dataStore Data store to use.
     * @param string    $key       Key to retrieve.
     */
    public function __construct(DataStore $dataStore, string $key)
    {
        $this->dataStore = $dataStore;
        $this->key       = $key;
    }

    /**
     * Return the string representation of the requested value.
     *
     * @return string
     */
    public function __toString()
    {
        return (string)$this->dataStore->get($this->key);
    }
}
