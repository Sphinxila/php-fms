<?php

/**
 *  This file is part of the PHPFms Project.
 *
 *  PHPFms is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  PHPFms is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with PHPFms.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace PHPFms;
use PHPFms\Exception\CryptError;
use PHPFms\Exception\PathError;
use PHPFms\Exception\WriteError;

class Directory {
    /** @var string */
    private $path;

    /**
     * Directory constructor.
     * @param string $path
     * @param bool $writable
     * @throws PathError
     */
    public function __construct(string $path, bool $writable = false)
    {
        // Initial path
        $this->changePath($path, $writable);
    }

    /**
     * Change path
     * @param string $path
     * @param bool $writable
     * @throws PathError
     */
    public function changePath(string $path, bool $writable = false)
    {
        // Path
        $this->path = realpath($path);

        // If not a directory
        if (!is_dir($this->path))
            throw new PathError("Selected path is not a directory", $this->path, 681);

        if ($writable === true && !is_writable($this->path))
            throw new PathError("Directory is not writable", $this->path, 682);
    }

    /**
     * @param string $name
     * @param string $mode
     * @param bool $open
     * @return File
     */
    public function getFile(string $name, string $mode = "r", bool $open = true)
    {
        $file = new File($this->path . "/" . basename($name), $mode, $open);
        return $file;
    }

    /**
     * Fast method to write content to a file like file_put_content
     * @param string $name
     * @param string $content
     * @param bool $append
     * @return bool
     */
    public function writeFile(string $name, string $content, bool $append = false)
    {
        // Default state
        $state = false;

        // Default mode
        $mode = "w+";

        // Append
        if ($append)
            $mode = "a+";

        $file = $this->getFile($name, $mode);

        try {
            // Write
            $file->write($content);
        } catch (WriteError $e) {
            Log::Exception($e);
        } catch (CryptError $e) {
            Log::Exception($e);
        }

        return $state;
    }

    /**
     * Files
     * @return array
     */
    public function getFiles(): array
    {
        $files = [];
        foreach ($it = $this->getIterator() as $filename => $file) {
            // Ignore dots
            if ($it->isDot()) {
                continue;
            }

            $file[$filename] = $file;
        }
        return $files;
    }

    /**
     * Get files by extension
     * @param $ext
     * @return array
     */
    public function getFilesByExtension($ext): array
    {
        return $this->getFilesByExtensions([$ext]);
    }

    /**
     * @param array $extensions
     * @return array
     */
    public function getFilesByExtensions(array $extensions): array
    {
        $files = [];
        foreach ($it = $this->getIterator() as $filename => $file) {
            // Ignore dots
            if ($it->isDot()) {
                continue;
            }

            // We just need js files
            $info = pathinfo($filename);
            if (!in_array($info['extension'], $extensions)) {
                continue;
            }

            $file[$filename] = $file;
        }
        return $files;
    }

    /**
     * Get iterator
     * @return \RecursiveIteratorIterator
     */
    public function getIterator(): \RecursiveIteratorIterator
    {
        $di = new \RecursiveDirectoryIterator($this->path);
        return (new \RecursiveIteratorIterator($di));
    }
}