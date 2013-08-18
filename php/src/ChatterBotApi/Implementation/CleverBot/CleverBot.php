<?php namespace ChatterBotApi\Implementation\CleverBot;

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

use ChatterBotApi\AbstractBot;

/**
 * A cleverbot
 */
class CleverBot extends AbstractBot
{
    /**
     * The url for this chatterbot
     * @var string
     */
    private $url;

    /**
     * Constructor.
     * @param string $url The url for this chatterbot
     */
    public function __construct($url)
    {
        $this->url = $url;
    }

    /**
     * Get the url of this chatterbot
     * @return string The url
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set the url for this chatterbot
     * @param string $url The url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * Create a new Session for this bot
     * @return \ChatterBotApi\Implementation\CleverBot\Session The new Session
     */
    public function createSession()
    {
        return new Session($this);
    }
}