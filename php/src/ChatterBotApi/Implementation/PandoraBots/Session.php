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

use Exception;
use SimpleXMLElement;
use ChatterBotApi\Utils;
use ChatterBotApi\AbstractSession;
use ChatterBotApi\ChatterBotThought;

class Session extends AbstractSession
{
    /**
     * Varibales used for creating the request
     * @var array
     */
    private $vars;

    /**
     * Constructor.
     * @param \ChatterBotApi\Implementation\PandoraBots $bot The bot
     */
    public function __construct(PandoraBots $bot)
    {
        $this->vars = array();
        $this->vars['botid']    = $bot->getId();
        $this->vars['custid']   = uniqid();
    }

    /**
     * Return new thought based on given thought
     * @param  \ChatterBotApi\ChatterBotTought $thought The previous thought
     * @return \ChatterBotApi\ChatterBotTought          The new thought.
     *
     * @throws \Exception If response is empty (when input string is empty)
     */
    public function thinkThought(ChatterBotThought $thought)
    {
        $this->vars['input'] = $thought->getText();
        

        $response = Utils::post('http://www.pandorabots.com/pandora/talk-xml', $this->vars);
        
        $element = new SimpleXMLElement($response);

        $result = $element->xpath('//result/that/text()');

        if (isset($result[0][0])) {
            return ChatterBotThought::make($result[0][0]);
        } else {
            throw new Exception('Empty Response');
            
        }
    }
}