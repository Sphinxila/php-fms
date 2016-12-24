<?php

// __DIR__ = Current directory
$path = __DIR__;
$buffer = "";

// Get current
try {
    // Create directory wrapper
    $directory = new \PHPFms\Directory($path, true);

    // Get file from __DIR__/test.file
    $file = $directory->getFile("test.file", "w+");

    // Empty
    if (!$file->read($buffer))
        echo "File is empty";
    else
        $file->write("Modified... - Old content was:" . $buffer);

    // Close
    $file->close();
} catch (\PHPFms\Exception\PathError $error) {
    echo $error->getMessage();
} catch (\PHPFms\Exception\PermissionError $error) {
    echo $error->getMessage();
}
