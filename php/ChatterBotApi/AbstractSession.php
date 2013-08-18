<?php namespace ChatterBotApi;

/*
 * ChatterBotAPI
 * Copyright (C) 2011 pierredavidbelanger@gmail.com
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

/**
 * Abstract for all ChatterBot Sessions
 */
abstract class AbstractSession
{
    /**
     * Return new thought based on given thought
     * @param  \ChatterBotApi\ChatterBotThought $thought The previous thought
     * @return \ChatterBotApi\ChatterBotThought          The new thought.
     */
    public function thinkThought(ChatterBotThought $thought)
    {
        return $thought;
    }

    /**
     * Return a new thought based on given string
     * @param  string $text The text
     * 
     * @return \ChatterBotApi\ChatterBotThought    The new thought.
     */
    public function think($text)
    {
        $thought = ChatterBotThought::make($text);
        return $this->thinkThought($thought);
    }
}