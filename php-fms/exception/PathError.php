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

namespace PHPFms\Exception;

class PathError extends \Exception {
    /** @var string */
    private $path = "";

    /**
     * PathError constructor.
     * @param string $message
     * @param string $path
     * @param int $code
     * @param \Exception $previous
     */
    public function __construct(string $message, string $path, int $code, ?\Exception $previous = null)
    {
        // Path
        $this->path = $path;

        // Build final message
        $finalMessage = sprintf("%s code: %d (Path: %s)", $message, $path, $code);

        // Call parent constructor
        parent::__construct($finalMessage, $code, $previous);

        // Free
        unset($finalMessage);
    }

    /**
     * Get path
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }
}