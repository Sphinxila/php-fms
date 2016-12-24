# PHP Filemanagementsystem / FMS #

This library allows you to manage your files easily.
With an easy interface you can set your own log library or crypto library.

The exception management is amazing!

The loader is using a cache system to avoid multiple handles for the same file.
To disable the handle caching use:

    PHPFms\Loader::DisableHandleCaching();

## Examples ##

Easy implementation
- [Read a file](examples/Read.php)
- [Modify a file](examples/Modify.php)
- [List files from a directory](examples/ListFiles.php)
- [Direct file handler](examples/DirectFile.php)
- [Use own logg](examples/Log.php)
- [Use on the fly encryption](examples/Encrypt.php)

## Installation ##
To use this library you need to add the following in your composer.json

    Sphinxila/php-fms

## License / Copying ##

This project is released under the GPL v3 license, so feel free to share
or modify this.

## Bug report ##
I only accept bug reports with an example code.
