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

use Exception;
use ChatterBotApi\Utils;
use ChatterBotApi\AbstractSession;
use ChatterBotApi\ChatterBotThought;

class Session extends AbstractSession
{
    /**
     * The assoc. bot of this session
     * @var \ChatterBotApi\Implementation\CleverBot\CleverBot
     */
    private $bot;

    /**
     * Varibales used for creating the request
     * @var array
     */
    private $vars;

    /**
     * Constructor.
     * @param \ChatterBotApi\Implementation\CleverBot\CleverBot $bot The bot
     */
    public function __construct(CleverBot $bot)
    {
        $this->bot                  = $bot;
        $this->vars                 = array();
        $this->vars['start']        = 'y';
        $this->vars['icognoid']     = 'wsf';
        $this->vars['fno']          = '0';
        $this->vars['sub']          = 'Say';
        $this->vars['islearning']   = '1';
        $this->vars['cleanslate']   = 'false';
    }

    /**
     * Return new thought based on given thought
     * @param  \ChatterBotApi\ChatterBotTought $thought The previous thought
     * @return \ChatterBotApi\ChatterBotTought          The new thought.
     */
    public function thinkThought(ChatterBotThought $thought)
    {
        $this->vars['stimulus'] = $thought->getText();
        
        $data = http_build_query($this->vars);
        
        $dataToDigest = substr($data, 9, 20);
        
        $dataDigest = md5($dataToDigest);
        
        $this->vars['icognocheck'] = $dataDigest;

        $response = Utils::post($this->bot->getUrl(), $this->vars);

        $responseValues = explode("\r", $response);

        // $this->vars['??']                    = Utils::stringAtIndex($responseValues, 0);
        $this->vars['sessionid']            = Utils::stringAtIndex($responseValues, 1);
        $this->vars['logurl']               = Utils::stringAtIndex($responseValues, 2);
        $this->vars['vText8']               = Utils::stringAtIndex($responseValues, 3);
        $this->vars['vText7']               = Utils::stringAtIndex($responseValues, 4);
        $this->vars['vText6']               = Utils::stringAtIndex($responseValues, 5);
        $this->vars['vText5']               = Utils::stringAtIndex($responseValues, 6);
        $this->vars['vText4']               = Utils::stringAtIndex($responseValues, 7);
        $this->vars['vText3']               = Utils::stringAtIndex($responseValues, 8);
        $this->vars['vText2']               = Utils::stringAtIndex($responseValues, 9);
        $this->vars['prevref']              = Utils::stringAtIndex($responseValues, 10);
        // $this->vars['??']                = Utils::stringAtIndex($responseValues, 11);
        $this->vars['emotionalhistory']     = Utils::stringAtIndex($responseValues, 12);
        $this->vars['ttsLocMP3']            = Utils::stringAtIndex($responseValues, 13);
        $this->vars['ttsLocTXT']            = Utils::stringAtIndex($responseValues, 14);
        $this->vars['ttsLocTXT3']           = Utils::stringAtIndex($responseValues, 15);
        $this->vars['ttsText']              = Utils::stringAtIndex($responseValues, 16);
        $this->vars['lineRef']              = Utils::stringAtIndex($responseValues, 17);
        $this->vars['lineURL']              = Utils::stringAtIndex($responseValues, 18);
        $this->vars['linePOST']             = Utils::stringAtIndex($responseValues, 19);
        $this->vars['lineChoices']          = Utils::stringAtIndex($responseValues, 20);
        $this->vars['lineChoicesAbbrev']    = Utils::stringAtIndex($responseValues, 21);
        $this->vars['typingData']           = Utils::stringAtIndex($responseValues, 22);
        $this->vars['divert']               = Utils::stringAtIndex($responseValues, 23);

        if ('' == Utils::stringAtIndex($responseValues, 16)) {
            throw new Exception('Empty Response');
        }
        return ChatterBotThought::make(Utils::stringAtIndex($responseValues, 16));
    }
}