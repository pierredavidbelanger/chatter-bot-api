package com.google.code.chatterbotapi;

import okhttp3.OkHttpClient;

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

    private final OkHttpClient httpClient;

    public ChatterBotFactory(OkHttpClient httpClient) {
        assert httpClient != null;
        this.httpClient = httpClient;
    }

    public ChatterBotFactory() {
        this(new OkHttpClient());
    }

    public ChatterBot create(ChatterBotType type) throws Exception {
        return create(type, null);
    }

    public ChatterBot create(ChatterBotType type, Object arg) throws Exception {
        assert type != null;
        switch (type) {
            case CLEVERBOT:
                if (arg == null) {
                    throw new Exception("CLEVERBOT needs an API Key. Please see https://www.cleverbot.com/api/");
                }
                return new Cleverbot(httpClient, arg.toString());
            case PANDORABOTS:
                if (arg == null) {
                    throw new Exception("PANDORABOTS needs a botid arg");
                }
                return new Pandorabots(arg.toString());
        }
        return null;
    }
}
