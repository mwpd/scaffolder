<?php

declare(strict_types=1);

namespace MWPD\Scaffolder;

use PHPUnit\Framework\TestCase;

/**
 * Class ManifestTest.
 *
 * @package MWPD\Scaffolder
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 *
 * @covers  MWPD\Scaffolder\Manifest
 */
class ManifestTest extends TestCase
{

    /**
     * @var Configuration
     */
    private $config;

    public function setUp(): void
    {
        $this->config = new Configuration(
            [
                'vendor'       => 'Bright Nucleus',
                'license'      => 'MIT',
                'author_name'  => 'Alain Schlesser',
                'author_email' => 'alain.schlesser@gmail.com',
            ]
        );
    }

    public function testItCanBeInstantiated(): void
    {
        $manifest = Manifest::fromFile(
            __DIR__ . '/fixtures/php-manifest-package/scaffolder-manifest.php',
            $this->config
        );
        $this->assertInstanceOf(Manifest::class, $manifest);
    }

    public function testItCanAcceptAnInjectedDataStoreInstance(): void
    {
        $manifest = Manifest::fromFile(
            __DIR__ . '/fixtures/php-manifest-package/scaffolder-manifest.php',
            $this->config,
            new DataStore()
        );
        $this->assertInstanceOf(Manifest::class, $manifest);
    }
}
