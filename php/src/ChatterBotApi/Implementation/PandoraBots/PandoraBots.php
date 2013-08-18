<?php namespace ChatterBotApi\Implementation\PandoraBots;

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
 * A Pandora Bot
 */
class PandoraBots extends AbstractBot
{   
    /**
     * The BotID
     * @var string
     */
    private $botid;

    /**
     * Constructor.
     * @param string $botid The Bot ID
     */
    public function __construct($botid)
    {
        $this->botid = $botid;
    }

    /**
     * Returns the bot ID
     * @return string The bot ID
     */
    public function getId()
    {
        return $this->botid;
    }

    /**
     * Set the bot ID
     * @param string $botid The bot ID
     */
    public function setId($botid)
    {
        $this->botid = $botid;
    }        

    /**
     * Create a new Session for this bot
     * @return \ChatterBotApi\Implementation\PandoraBots\Session The new Session
     */
    public function createSession()
    {
        return new Session($this);
    }
}