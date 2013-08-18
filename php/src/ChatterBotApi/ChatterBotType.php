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
 * The ChatterBot type
 */
class ChatterBotType
{
    /**
     * CleverBot
     * @var int 1
     */
    const CLEVERBOT = 1;
    
    /**
     * JabberWacky
     * @var int 2
     */
    const JABBERWACKY = 2;
    
    /**
     * PandoraBot
     * @var int 3
     */
    const PANDORABOTS = 3;

    /**
     * A "good" bot ID
     * @var string
     */
    const PANDORABOTS_DEFAULT_ID = 'b0dafd24ee35a477';
}