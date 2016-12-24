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
use PHPFms\Exception\PathError;
use PHPFms\Exception\PermissionError;
use PHPFms\Exception\SystemError;
use PHPFms\Exception\TypeError;

/**
 * Handle manager
 * Class Loader
 * @package PHPFms
 */

class Loader {
    /** @var resource[] */
    private static $handles = [];

    /** @var bool */
    private static $cacheHandle = true;

    /** @var array */
    private static $error = [];

    /**
     * Disables cache handling
     * @param bool $state
     */
    public static function DisableHandleCaching(bool $state = true): void
    {
        self::$cacheHandle = $state === true ? false : true;
        return;
    }

    /**
     * @param string $path
     * @param string $option
     * @param string|null $owner
     * @param int|null $size
     * @return resource
     * @throws PathError
     * @throws PermissionError
     */
    public static function open(string $path, string $option = "r", ?string &$owner = '', ?int &$size = null): ?resource
    {
        // Key
        $key = md5($path . $option);
        $create = true;

        // If already opened
        if (array_key_exists($key, self::$handles)) {
            return self::$handles[$key];
        }

        // Create
        if (strpos($option, "+") === false)
            $create = false;

        if (!$create && !file_exists($path))
            throw new PathError("File noe exists", $path, 691);

        // Fopen
        $hFile = @fopen($path, $option);

        // If everything fails
        if ($create && !is_resource($hFile))
            throw new PermissionError("Permission denied", $path, 500);

        // Get file size
        $size = filesize($path);

        // Check if resource is valid
        if (!is_resource($hFile))
            self::$error = error_get_last();

        // Cache handler
        if ($hFile && self::$cacheHandle === true) {
            self::$handles[$key] = $hFile;
        }

        // Return by reference (key as owner)
        $owner = $key;
        return $hFile;
    }

    /**
     * @param resource $hFile
     * @return bool
     * @throws TypeError
     */
    public static function close(resource $hFile): bool
    {
        // Check if valid resource
        if (!is_resource($hFile))
            throw new TypeError("Invalid resource", 404);

        // Remove from cache handler
        if (self::$cacheHandle === true && $key = array_search($hFile, self::$handles) !== false)
            unset(self::$handles[$key]);

        return @fclose($hFile) !== false;
    }

    /**
     * @param string $key
     * @return bool
     * @throws SystemError
     * @throws TypeError
     */
    public static function closeByOwner(string $key): bool
    {
        // If cache handling is disabled we have no array with owner handles
        if (self::$cacheHandle === false)
            throw new SystemError("Handle caching disabled, there is no handle owner");

        // Check for handle
        if (array_key_exists($key, self::$handles)) {
            // Get from handle cache
            $hFile = self::$handles[$key];

            // Check if valid resource
            if (!is_resource($hFile))
                throw new TypeError("Invalid resource", 404);

            // CLose handle
            $state = @fclose($hFile) !== false;

            // Remove from cache
            unset(self::$handles[$key]);

            return $state;
        }
        return false;
    }
}