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
 * Utils class 
 */
class Utils
{
	/**
	 * Post to the given URL
	 * @param  string $url    The target url
	 * @param  array  $params The parameters
	 * @return string         The response
	 */
	public static function post($url, $params)
	{
		$contextParams = array();
		$contextParams['http'] = array();
		$contextParams['http']['method'] = 'POST';
		$contextParams['http']['content'] = http_build_query($params);
		$context = stream_context_create($contextParams);
		$fp = fopen($url, 'rb', false, $context);
		$response = stream_get_contents($fp);
		fclose($fp);
		return $response;
	}

	/**
	 * Returns the string at the given index
	 * @param  array  $strings The strings
	 * @param  int    $index   The index
	 * @return string          The string | ''
	 */
	public static function stringAtIndex($strings, $index)
	{
		if (count($strings) > $index) {
			return $strings[$index];
		} else {
			return '';
		}
	}
}