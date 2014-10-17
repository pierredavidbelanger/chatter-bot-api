<?php
/*
 * ChatterBotAPI Tests
 * Copyright (C) 2013 christiangaertner.film@googlemail.com
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

use ChatterBotApi\ChatterBotFactory;

/**
* PHPUnit Test
*/
class ChatterBotFactoryTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @dataProvider createData
	 */
	public function testCreate($type, $arg, $exp)
	{
		$this->assertInstanceOf($exp, ChatterBotFactory::create($type, $arg));
	}

	public function createData()
	{
		return array(
			array(1, null, 'ChatterBotApi\\Implementation\\CleverBot\\CleverBot'),
			array(1, null, 'ChatterBotApi\\AbstractBot'),

			array(2, null, 'ChatterBotApi\\Implementation\\CleverBot\\CleverBot'),
			array(2, null, 'ChatterBotApi\\AbstractBot'),

			array(3, 'b0dafd24ee35a477', 'ChatterBotApi\\Implementation\\PandoraBots\\PandoraBots'),
			array(3, 'b0dafd24ee35a477', 'ChatterBotApi\\AbstractBot')
			);
	}

	/**
	 * @expectedException Exception
	 */
	public function testCreateException()
	{
		ChatterBotFactory::create(3);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testCreateInvalidArgumentException()
	{
		ChatterBotFactory::create('Foo');
	}
}