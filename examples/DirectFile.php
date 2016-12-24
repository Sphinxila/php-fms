<?php
$path = __DIR__ . "/direct.file";

try {
    $file = new \PHPFms\File($path, "w+");
    $file->write("Is this real :S\n");
    $file->write("Im not sure\n");
    $file->write("This library is so awesome...");
    $file->write("But I can also use the Illuminate/FileSystem...");
    $file->close();
} catch (\PHPFms\Exception\PathError $error) {
    echo $error->getMessage();
} catch (\PHPFms\Exception\PermissionError $error) {
    echo $error->getMessage();
}
