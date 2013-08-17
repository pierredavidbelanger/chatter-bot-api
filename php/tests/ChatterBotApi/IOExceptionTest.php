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

use ChatterBotApi\IOException;

/**
* PHPUnit Test
*/
class IOExceptionTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @dataProvider contructData
	 */
	public function testContruct($path, $exp)
	{
		$e = new IOException('Foo', 1, $path);

		$rp = new ReflectionProperty($e, 'path');
		$rp->setAccessible(true);

		$this->assertEquals($exp, $rp->getValue($e));
	}

	public function contructData()
	{
		return array(
			array('foo/bar', 'foo/bar'),
			array('', '<EMPTY STRING GIVEN>'),
			array(null, null),
			);
	}

	public function testGetpath()
	{
		$e = new IOException('Foo', 1, 'foo/bar');

		$this->assertEquals('foo/bar', $e->getPath());
	}

	public function testToString()
	{
		$e = new IOException('Foo', 1, 'foo/bar');

		$this->assertEquals("ChatterBotApi\IOException: [1]: Foo\nIO path: foo/bar\n", (string) $e);
	}
}