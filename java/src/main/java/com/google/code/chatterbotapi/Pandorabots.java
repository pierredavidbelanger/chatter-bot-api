package com.google.code.chatterbotapi;

import java.util.LinkedHashMap;
import java.util.Map;
import java.util.UUID;

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
class Pandorabots implements ChatterBot {
    private final String botid;

    public Pandorabots(String botid) {
        this.botid = botid;
    }

    @Override
    public ChatterBotSession createSession() {
        return new Session();
    }

    private class Session implements ChatterBotSession {
        private final Map<String, String> vars;

        public Session() {
            vars = new LinkedHashMap<String, String>();
            vars.put("botid", botid);
            vars.put("custid", UUID.randomUUID().toString());
        }

        @Override
        public ChatterBotThought think(ChatterBotThought thought) throws Exception {
            vars.put("input", thought.getText());

            String response = Utils.post("http://www.pandorabots.com/pandora/talk-xml", vars);

            ChatterBotThought responseThought = new ChatterBotThought();

            responseThought.setText(Utils.xPathSearch(response, "//result/that/text()"));

            return responseThought;
        }

        @Override
        public String think(String text) throws Exception {
            ChatterBotThought thought = new ChatterBotThought();
            thought.setText(text);
            return think(thought).getText();
        }
    }
}