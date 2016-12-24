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
use PHPFms\Exception\TypeError;
use PHPFms\Exception\WriteError;

/**
 * File abstraction layer
 * Class File
 * @package PHPFms
 */

class File {
    /** @var string */
    private $path;

    /** @var string */
    private $mode;

    /** @var bool */
    private $open;

    /** @var null */
    private $hFile = null;

    /** @var null */
    private $owner = null;

    /** @var int */
    private $size = 0;

    /** @var Template\CryptEngine */
    private $engine = null;

    /**
     * File constructor.
     * @param string $path
     * @param string $mode
     * @param bool $open
     * @throws Exception\PathError
     * @throws Exception\PermissionError
     */
    public function __construct(string $path, string $mode, bool $open = true)
    {
        $this->path = realpath($path);
        $this->mode = $mode;
        $this->open = $open;

        // Open
        if ($open === true)
            $this->open();
    }

    /**
     * Set engine
     * @param $engine
     */
    public function setCryptEngine($engine): void
    {
        $this->engine = $engine;
    }

    /**
     * Open
     * @throws Exception\PathError
     * @throws Exception\PermissionError
     * @return bool
     */
    public function open(): bool
    {
        $this->hFile = Loader::open($this->path, $this->mode, $this->owner, $this->size);
        return is_resource($this->hFile) ? true : false;
    }

    /**
     * Write content to file
     * @param string $content
     * @return int
     * @throws CryptError
     * @throws WriteError
     */
    public function write(string $content): int
    {
        // Check if handle is valid
        if (!is_resource($this->hFile))
            throw new WriteError("Write failed", $this->path, 512);

        // Crypt engine
        if ($this->engine) {
            if (!$this->engine->encrypt($content))
                throw new CryptError("Failed to encrypt content for " . $this->path);
        }

        // Write
        return fwrite($this->hFile, $content, strlen($content));
    }

    /**
     * @param array $data
     * @return int
     */
    public function writeEncoded(array $data)
    {
        $encode = bin2hex(json_encode($data));
        return $this->write($encode);
    }

    /**
     * Read file content
     * @param null|string|null $buffer
     * @param int $size
     * @return bool
     * @throws CryptError
     */
    public function read(?string &$buffer = null, int $size = 0): bool
    {
        $buffer = fread($this->hFile, $size > 0 ? $size : $this->size);

        // Cryüt engine
        if ($this->engine) {
            if (!$this->engine->decrypt($content))
                throw new CryptError("Failed to decrypt content from " . $this->path);
        }

        return strlen($buffer) > 0 ? true : false;
    }

    /**
     * @param array $data
     * @return bool
     * @throws TypeError
     */
    public function readDecoded(array &$data): bool
    {
        $buffer = "";
        if ($this->read($buffer)) {
            $temp = json_decode($buffer, true);
            if (is_null($temp))
                throw new TypeError("Failed to decode json", 333);
        }
    }

    /**
     * Close handle
     * @return bool
     */
    public function close(): bool
    {
        if ($this->owner)
            return Loader::closeByOwner($this->owner);
        else
            return Loader::close($this->hFile);
    }
}