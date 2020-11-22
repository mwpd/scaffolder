<?php

declare(strict_types=1);

namespace MWPD\Scaffolder;

use Closure;
use MWPD\Scaffolder\Exception\InvalidManifestFile;

final class Manifest
{

    public const VERSION     = 'version';
    public const NAME        = 'name';
    public const DESCRIPTION = 'description';
    public const PROMPTS     = 'prompts';

    /**
     * Metadata about the manifest itself.
     *
     * @var array
     */
    private $metadata;

    /**
     * Associative array of prompts.
     *
     * @var Prompt[]
     */
    private $prompts;

    /**
     * Configuration instance to use.
     *
     * @var Configuration
     */
    private $config;

    /**
     * DataStore instance to use.
     *
     * @var DataStore
     */
    private $dataStore;

    /**
     * Instantiate a Manifest object.
     *
     * The constructor is private to force instantiation through named constructors instead.
     *
     * @param Configuration  $config    Configuration object to use.
     * @param DataStore|null $dataStore Optional. Data store instance to use.
     */
    private function __construct(Configuration $config, DataStore $dataStore = null)
    {
        $this->config    = $config;
        $this->dataStore = $dataStore ?? new DataStore();
    }

    /**
     * Create a new manifest through loading a manifest file.
     *
     * @param string         $filepath  Filepath to load as manifest data.
     * @param Configuration  $config    Configuration object to use.
     * @param DataStore|null $dataStore Optional. Data store instance to use.
     * @return self
     */
    public static function fromFile(string $filepath, Configuration $config, DataStore $dataStore = null): self
    {
        if (! is_file($filepath) || ! is_readable($filepath)) {
            throw InvalidManifestFile::fromFile($filepath);
        }

        // @todo Check version first.

        $manifest = new self($config, $dataStore);
        $manifest->loadManifestFile($filepath);

        return $manifest;
    }

    public function getPromptedData(UserInput $userInput, Logger $logger): array
    {
        $results = [];

        foreach ($this->prompts as $key => $prompt) {
            $results[] = $prompt->execute($userInput, $logger);
        }

        return $results;
    }

    public function config(string $key)
    {
        return $this->config->get($key);
    }

    private function loadManifestFile(string $filepath): void
    {
        $closure = Closure::bind(
            function () use ($filepath) {
                return include $filepath;
            },
            $this,
            static::class
        );

        $this->processData($closure());
    }

    private function processData(array $manifestData): void
    {
        $this->metadata[self::VERSION]     = $manifestData[self::VERSION];
        $this->metadata[self::NAME]        = $manifestData[self::NAME];
        $this->metadata[self::DESCRIPTION] = $manifestData[self::DESCRIPTION];
        $this->prompts                     = $manifestData[self::PROMPTS];
    }

    public function __get(string $name)
    {
        return new DelayedDataStoreValue($this->dataStore, $name);
    }
}
