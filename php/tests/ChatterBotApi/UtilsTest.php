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

use ChatterBotApi\Utils;

/**
* PHPUnit Test
*/
class UtilsTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @dataProvider stringAtIndexData
	 */
	public function testStringAtIndex($strings, $index, $exp)
	{
		$this->assertEquals($exp, Utils::stringAtIndex($strings, $index));
	}

	public function stringAtIndexData()
	{
		return array(
				array(
					array('Foo', 'Bar', 'Baz'),
					0,
					'Foo'
				),
				array(
					array('Foo', 'Bar', 'Baz'),
					1,
					'Bar'
				),
				array(
					array('Foo', 'Bar', 'Baz'),
					2,
					'Baz'
				),
				array(
					array('Foo', 'Bar', 'Baz'),
					8,
					''
				),
			);
	}
}