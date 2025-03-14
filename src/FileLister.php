<?php

namespace App;

use Symfony\Component\HttpKernel\KernelInterface;

class FileLister
{
    protected string $directory;
    protected string $projectDir;

    public function __construct(KernelInterface $kernel, string $directory)
    {
        $this->projectDir = $kernel->getProjectDir();
        $this->directory = $directory;
    }

    /**
     * @return string[]
    */
    public function listFiles(): array
    {
        $files = [];
        $fullPath = $this->projectDir . '/' . $this->directory;
        $this->scanDirectory($fullPath, $files);
        return $files;
    }

    /**
     * @param string $directory
     * @param string[] &$files
     */
    protected function scanDirectory(string $directory, array &$files): void
    {
        if (!is_dir($directory)) {
            return;
        }

        if ($handle = opendir($directory)) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") {
                    $path = $directory . DIRECTORY_SEPARATOR . $entry;
                    if (is_dir($path)) {
                        $this->scanDirectory($path, $files);
                    } else {
                        $files[] = $path;
                    }
                }
            }
            closedir($handle);
        }
    }
}
