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
    
    require 'chatterbotapi.php';
    
    $factory = new ChatterBotFactory();
    
    $bot1 = $factory->create(ChatterBotType::CLEVERBOT);
    $bot1session = $bot1->createSession();
    
    $bot2 = $factory->create(ChatterBotType::PANDORABOTS, 'b0dafd24ee35a477');
    $bot2session = $bot2->createSession();
    
    $s = 'Hi';
    while (1) 
    {
        echo "bot1> $s\n";
        
        $s = $bot2session->think($s);
        echo "bot2> $s\n";
        
        $s = $bot1session->think($s);
    }
?>