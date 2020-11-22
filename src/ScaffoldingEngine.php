<?php

declare(strict_types=1);

namespace MWPD\Scaffolder;

use FilesystemIterator;
use MWPD\Scaffolder\TemplateEngine\MustacheTemplateEngine;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

final class ScaffoldingEngine
{

    /**
     * @var TemplateEngine
     */
    private $templateEngine;

    /**
     * @var DataStore
     */
    private $dataStore;

    /**
     * Source location to delete on object destruction.
     *
     * @var string|false Source path to delete, or false if no deletion
     *      requested.
     */
    private $sourceToDelete = false;

    public function __construct(TemplateEngine $templateEngine = null, DataStore $dataStore = null)
    {
        $this->templateEngine = $templateEngine ?? new MustacheTemplateEngine();
        $this->dataStore      = $dataStore ?? new DataStore();
    }

    /**
     * Scaffold a new set of target files from a provided set of input template
     * files.
     *
     * @param string $source Source file/folder/URL th«at contains the template
     *                       file(s).
     * @param string $target Target file/folder that receives the generated
     *                       result.
     * @return bool Whether the scaffolding operation succeeded.
     */
    public function scaffold(string $source, string $target): bool
    {
        if ($this->isRemoteUrl($source)) {
            $source               = $this->downloadRemoteUrl($source);
            $this->sourceToDelete = $source;
        }

        foreach ($this->interpretSource($source) as $sourceFilepath) {
            $input   = file_get_contents($sourceFilepath);
            $context = new Context(array_merge($this->dataStore->toArray(), [Context::FILEPATH => $sourceFilepath]));
            if (! $this->templateEngine->canProcess($input, $context)) {
                // TODO
            }
            $output         = $this->templateEngine->process($input, $context);
            $targetFilepath = $this->getTargetFilepath($source, $target, $sourceFilepath);
            file_put_contents($targetFilepath, $output);
        }

        return true;
    }

    /**
     * Interpret the $source string to find all source file paths.
     *
     * @param string $source Source string to interpret.
     * @return string[] Array of source file paths.
     */
    private function interpretSource(string $source): array
    {
        // TODO

        return [$source];
    }

    /**
     * Get the target file path from a given source and source file path context.
     *
     * @param string $source     Source string that was used.
     * @param string $target     Target string that was used.
     * @param string $sourceFile Array of source file paths that were detected.
     * @return string Target file path to use.
     */
    private function getTargetFilepath(string $source, string $target, string $sourceFile): string
    {
        // TODO
        if ($sourceFile === $source) {
            return $target;
        }
    }

    private function isRemoteUrl(string $url): bool
    {
        return parse_url($url, PHP_URL_HOST) !== null;
    }

    private function downloadRemoteUrl(string $url): string
    {
        $temporaryFilePath = $this->getTemporaryFilePath($url);

        // TODO: Deal with folders and archives.

        $success = file_put_contents($temporaryFilePath, file_get_contents($url));

        if ($success === false) {
            // TODO
        }

        return $temporaryFilePath;
    }

    private function getTemporaryFilePath(string $path)
    {
        $hash = md5($path);

        return tempnam(sys_get_temp_dir(), "scaffolder-{$hash}-");
    }

    private function isWithinTemporaryFolder(string $path): bool
    {
        $tempDir = sys_get_temp_dir();

        return strpos($path, $tempDir) === 0 || strpos($path, "/private$tempDir") === 0;
    }

    private function recursiveDelete(string $path): void
    {
        if (is_file($path)) {
            unlink($path);

            return;
        }

        $filesAndFolders = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(
                $path,
                FilesystemIterator::SKIP_DOTS | FilesystemIterator::UNIX_PATHS
            ),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($filesAndFolders as $fileOrFolder) {
            $fileOrFolder->isFile() ? unlink($fileOrFolder) : rmdir($fileOrFolder);
        }

        rmdir($path);
    }

    private function ensureTrailingSlash(string $path): string
    {
        return rtrim($path, '/') . '/';
    }

    /**
     * Glob that is safe with streams (vfs for example).
     *
     * @param string $directory
     * @param string $filePattern
     * @return array
     */
    private function streamSafeGlob($directory, $filePattern)
    {
        $files = scandir($directory);
        $found = [];

        foreach ($files as $filename) {
            if (in_array($filename, ['.', '..'])) {
                continue;
            }

            if (fnmatch($filePattern, $filename)) {
                $found[] = "{$directory}/{$filename}";
            }
        }

        return $found;
    }

    public function __destruct()
    {
        if ($this->sourceToDelete === false) {
            return;
        }

        if (! $this->isWithinTemporaryFolder($this->sourceToDelete)) {
            return;
        }

        $this->recursiveDelete($this->sourceToDelete);
    }
}
