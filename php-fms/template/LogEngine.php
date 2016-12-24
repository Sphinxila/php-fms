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

namespace PHPFms\Template;

abstract class LogEngine {
    /**
     * Error
     * @param string $buffer
     * @return bool
     */
    abstract public function error(string $buffer): bool;

    /**
     * Info
     * @param string $buffer
     * @return bool
     */
    abstract public function info(string $buffer): bool;
}