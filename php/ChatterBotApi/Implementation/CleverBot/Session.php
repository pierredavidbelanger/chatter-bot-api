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

use ChatterBotApi\ChatterBotTought;
use ChatterBotApi\ChatterBotSession;

class Session extends ChatterBotSession
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
		$this->bot = $bot;
		$this->vars = array();
		$this->vars['start'] = 'y';
		$this->vars['icognoid'] = 'wsf';
		$this->vars['fno'] = '0';
		$this->vars['sub'] = 'Say';
		$this->vars['islearning'] = '1';
		$this->vars['cleanslate'] = 'false';
	}

	/**
	 * Return new thought based on given thought
	 * @param  \ChatterBotApi\ChatterBotTought $thought The previous thought
	 * @return \ChatterBotApi\ChatterBotTought          The new thought.
	 */
	public function thinkThought(ChatterBotTought $thought)
	{
		$this->vars['stimulus'] = $thought->getText();
		$data = http_build_query($this->vars);
		$dataToDigest = substr($data, 9, 20);
		$dataDigest = md5($dataToDigest);
		$this->vars['icognocheck'] = $dataDigest;
		$response = _utils_post($this->bot->getUrl(), $this->vars);
		$responseValues = split("\r", $response);
		//self.vars['??'] = _utils_string_at_index($responseValues, 0);
		$this->vars['sessionid'] = _utils_string_at_index($responseValues, 1);
		$this->vars['logurl'] = _utils_string_at_index($responseValues, 2);
		$this->vars['vText8'] = _utils_string_at_index($responseValues, 3);
		$this->vars['vText7'] = _utils_string_at_index($responseValues, 4);
		$this->vars['vText6'] = _utils_string_at_index($responseValues, 5);
		$this->vars['vText5'] = _utils_string_at_index($responseValues, 6);
		$this->vars['vText4'] = _utils_string_at_index($responseValues, 7);
		$this->vars['vText3'] = _utils_string_at_index($responseValues, 8);
		$this->vars['vText2'] = _utils_string_at_index($responseValues, 9);
		$this->vars['prevref'] = _utils_string_at_index($responseValues, 10);
		//$this->vars['??'] = _utils_string_at_index($responseValues, 11);
		$this->vars['emotionalhistory'] = _utils_string_at_index($responseValues, 12);
		$this->vars['ttsLocMP3'] = _utils_string_at_index($responseValues, 13);
		$this->vars['ttsLocTXT'] = _utils_string_at_index($responseValues, 14);
		$this->vars['ttsLocTXT3'] = _utils_string_at_index($responseValues, 15);
		$this->vars['ttsText'] = _utils_string_at_index($responseValues, 16);
		$this->vars['lineRef'] = _utils_string_at_index($responseValues, 17);
		$this->vars['lineURL'] = _utils_string_at_index($responseValues, 18);
		$this->vars['linePOST'] = _utils_string_at_index($responseValues, 19);
		$this->vars['lineChoices'] = _utils_string_at_index($responseValues, 20);
		$this->vars['lineChoicesAbbrev'] = _utils_string_at_index($responseValues, 21);
		$this->vars['typingData'] = _utils_string_at_index($responseValues, 22);
		$this->vars['divert'] = _utils_string_at_index($responseValues, 23);
		$responseThought = new ChatterBotThought();
		$responseThought->setText(_utils_string_at_index($responseValues, 16));
		return $responseThought;
	}
}