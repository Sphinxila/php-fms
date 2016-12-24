<?php

// __DIR__ = Current directory
$path = __DIR__;
$buffer = "";

// Get current
try {
    // Create directory wrapper
    $directory = new \PHPFms\Directory($path, true);

    // Get all files
    print_r($directory->getFiles());

    // Get all files ending with php(filetype)
    print_r($directory->getFilesByExtension("php"));
} catch (\PHPFms\Exception\PathError $error) {
    echo $error->getMessage();
}