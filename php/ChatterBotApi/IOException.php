<?php namespace ChatterBotApi;

/*
 * ChatterBotAPI
 * Copyright (C) 2011 pierredavidbelanger@gmail.com
 * Copyright (C) 2013 christiangaertner.film@googlemail.com (This file)
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

use Exception;

/**
 * An IO Exception
 * @author Christian Gärtner <christiangaertner.film@googlemail.com>
 */
class IOException extends Exception
{
    private $path;

    public function __construct($message, $code = 0, $path = null, Exception $previous = null)
    {
        if ($path === '' && $path !== null) {
            $path = '<EMPTY STRING GIVEN>';
        }
        $this->path = $path;
        parent::__construct($message, $code, $previous);
    }

    /**
     * Magic Function. __toString
     * @return string The exception message
     * @Override
     */
    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\nIO path: {$this->path}\n";
    }

    /**
     * Returns the path of this IOException
     * @return string The path
     */
    public function getPath()
    {
        return $this->path;
    }
}