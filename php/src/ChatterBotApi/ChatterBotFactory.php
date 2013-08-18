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

use Exception;
use InvalidArgumentException;
use ChatterBotApi\Implementation\CleverBot\CleverBot;
use ChatterBotApi\Implementation\PandoraBots\PandoraBots;

/** 
 * The ChatterBotFactory
 */
class ChatterBotFactory
{
    /**
     * Create a new a ChatterBot
     * @param  int    $type The ChatterBotType (hint: use the ChatterBotType constants)
     * @param  string $arg  BotId (only needed when using a PandoraBot)
     *
     * @return \ChatterBotApi\ChatterBot       The new Bot instance
     *
     * @throws \Exception When type not recognized or no BotId bassed to the PandoraBot
     */
    public static function create($type, $arg = null)
    {
        switch ($type) {
            case ChatterBotType::CLEVERBOT:
                return new Cleverbot('http://www.cleverbot.com/webservicemin');
            

            case ChatterBotType::JABBERWACKY:
                return new Cleverbot('http://jabberwacky.com/webservicemin');
            
            case ChatterBotType::PANDORABOTS:
                if ($arg == null) {
                    throw new Exception('PANDORABOTS needs a botid arg');
                }
            return new PandoraBots($arg);

            default:
                throw new InvalidArgumentException('Type not recognized');
                
        }
    }
}