package com.google.code.chatterbotapi;

/*
    chatter-bot-api
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
public class ChatterBotFactory {

    public ChatterBot create(ChatterBotType type) throws Exception {
        return create(type, null);
    }

    public ChatterBot create(ChatterBotType type, Object arg) throws Exception {
        switch (type) {
            case CLEVERBOT:
                return new Cleverbot("http://www.cleverbot.com", "http://www.cleverbot.com/webservicemin?uc=321", 35);
            case JABBERWACKY:
                return new Cleverbot("http://jabberwacky.com", "http://jabberwacky.com/webservicemin", 29);
            case PANDORABOTS:
                if (arg == null) {
                    throw new Exception("PANDORABOTS needs a botid arg");
                }
                return new Pandorabots(arg.toString());
        }
        return null;
    }
}
