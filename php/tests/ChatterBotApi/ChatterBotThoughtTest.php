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

use ChatterBotApi\ChatterBotThought;

/**
* PHPUnit Test
*/
class ChatterBotThoughtTest extends PHPUnit_Framework_TestCase
{
	public function testMake()
	{
		$this->assertInstanceOf('ChatterBotApi\ChatterBotThought', ChatterBotThought::make());
	}

	/**
	 * @dataProvider constructData
	 */
	public function testConstruct($text, $exp)
	{
		if ($text === null) {
			$t = new ChatterBotThought();
		} else {
			$t = new ChatterBotThought($text);
		}
		
		$rp = new ReflectionProperty($t, 'text');
		$rp->setAccessible(true);

		$this->assertEquals($exp, $rp->getValue($t));

	}

	public function constructData()
	{
		return array(
			array(null, ''),
			array('Foo', 'Foo')
			);
	}

	public function testToString()
	{
		$t = ChatterBotThought::make('Foo');

		$this->assertEquals('Foo', (string) $t);
	}

	public function testGetText()
	{
		$t = ChatterBotThought::make('Foo');

		$this->assertEquals('Foo', $t->getText());
	}

	public function testSetText()
	{
		$t = ChatterBotThought::make('Foo');
		$t->setText('Bar');
		$this->assertEquals('Bar', $t->getText());
	}

	public function testMessage()
	{
		$t = ChatterBotThought::make('Foo');

		$this->assertEquals('Foo', $t->message());
	}
}