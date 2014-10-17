<?php
    /*
     ChatterBotAPI
     Copyright (C) 2011 pierredavidbelanger@gmail.com
     
     This program is free software: you can redistribute it and/or modify
     it under the terms of the GNU Lesser General Public License as published by
     the Free Software Foundation, either version 3 of the License, or
     (at your option) any later version.
     
     This program is distributed in the hope that it will be useful,
     but WITHOUT ANY WARRANTY; without even the implied warranty of
     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     GNU Lesser General Public License for more details.
     
     You should have received a copy of the GNU Lesser General Public License
     along with this program.  If not, see <http://www.gnu.org/licenses/>.
    */
    
    /**
     * This tests were written by:
     * Christian Gärtner <christiangaertner.film@googlemail.com>
     */
     
require 'vendor/autoload.php';


use ChatterBotApi\ChatterBotType;
use ChatterBotApi\ChatterBotThought;
use ChatterBotApi\ChatterBotFactory;

$bot1 = ChatterBotFactory::create(ChatterBotType::CLEVERBOT);
$bot1session = $bot1->createSession();

$bot2 = ChatterBotFactory::create(ChatterBotType::PANDORABOTS, ChatterBotType::PANDORABOTS_DEFAULT_ID);
$bot2session = $bot2->createSession();

$th = ChatterBotThought::make('Hi');

while (1)
{
    echo "bot1> $th\n";

    try {
        $th = $bot2session->think($th->message());
    } catch (IOException $e) {
        echo $e;
    } catch (Exception $e) {
        // Ignore these
    }

    echo "bot2> $th\n";

    try {
        $th = $bot1session->think($th->message());
    } catch (IOException $e) {
        echo $e;
    } catch (Exception $e) {
        // Ignore these
    }
    
}