<?php

$sourceDir = __DIR__ . '/../node_modules/leaflet/dist';
$destDir = __DIR__ . '/../public/lib/leaflet';
$tailwindDir = __DIR__ . '/../public/css';

function createDirectoryIfNotExists($directory) {
    if (!is_dir($directory)) {
        mkdir($directory, 0755, true);
    }
}

function copyFiles($source, $dest) {
    if (is_dir($source)) {
        $dirHandle = opendir($source);
        if ($dirHandle) {
            while (($file = readdir($dirHandle)) !== false) {
                if ($file != '.' && $file != '..') {
                    $sourceFile = $source . '/' . $file;
                    $destFile = $dest . '/' . $file;
                    if (is_dir($sourceFile)) {
                        createDirectoryIfNotExists($destFile);
                        copyFiles($sourceFile, $destFile);
                    } else {
                        copy($sourceFile, $destFile);
                    }
                }
            }
            closedir($dirHandle);
        }
    }
}

shell_exec('npm install');

createDirectoryIfNotExists($destDir);

copyFiles($sourceDir, $destDir);

createDirectoryIfNotExists($tailwindDir);
shell_exec('npx tailwindcss -i ' . __DIR__ . '/../assets/styles/input.css -o ' . __DIR__ . '/../public/lib/output.css');

?>
