<?php declare(strict_types=1);

use MWPD\Scaffolder\Manifest;
use MWPD\Scaffolder\Prompt;

return [
    Manifest::VERSION     => 'v1',
    Manifest::NAME        => 'php-manifest-package',
    Manifest::DESCRIPTION => 'Test manifest using PHP.',

    Manifest::PROMPTS => [
        'vendorName'      => new Prompt\Name('Name of the vendor', $this->config('vendor')),
        'packageName'     => new Prompt\Name('Name of the package'),
        'repositoryName'  => new Prompt\Package('Name of the repository', $this->vendorName, '/', $this->packageName),
        'license'         => new Prompt\License('License to use', $this->config('license')),
        'authorName'      => new Prompt\Name('Name of the author', $this->config('author_name')),
        'authorEmail'     => new Prompt\Email('Email of the author', $this->config('author_email')),
        'rootNamespace'   => new Prompt\FullyQualifiedNamespace('Root namespace of the package', $this->vendorName->inCamelCase(), '\\', $this->packageName->inCamelCase(), '\\'),
        'rootFolder'      => new Prompt\RelativeFolder('Root folder for the package'),
        'testsNamespace'  => new Prompt\FullyQualifiedNamespace('Namespace of the tests for the package', $this->vendorName->inCamelCase(), '\\', $this->packageName->inCamelCase(), '\\Tests\\'),
    ],
];
