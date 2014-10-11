package com.google.code.chatterbotapi;

import java.util.LinkedHashMap;
import java.util.Map;

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
class Cleverbot implements ChatterBot {
    private final String url;
    private int endIndex;

    public Cleverbot(String url, int endIndex) {
        this.url = url;
        this.endIndex = endIndex;
    }

    @Override
    public ChatterBotSession createSession() {
        return new Session();
    }
    
    private class Session implements ChatterBotSession {
        private final Map<String, String> vars;

        public Session() {
            vars = new LinkedHashMap<String, String>();
            vars.put("start", "y");
            vars.put("icognoid", "wsf");
            vars.put("fno", "0");
            vars.put("sub", "Say");
            vars.put("islearning", "1");
            vars.put("cleanslate", "false");
        }
        
        @Override
        public ChatterBotThought think(ChatterBotThought thought) throws Exception {
            vars.put("stimulus", thought.getText());

            String formData = Utils.parametersToWWWFormURLEncoded(vars);
            String formDataToDigest = formData.substring(9, endIndex);
            String formDataDigest = Utils.md5(formDataToDigest);
            vars.put("icognocheck", formDataDigest);

            String response = Utils.post(url, vars);
            
            String[] responseValues = response.split("\r");
            
            //vars.put("", Utils.stringAtIndex(responseValues, 0)); ??
            vars.put("sessionid", Utils.stringAtIndex(responseValues, 1));
            vars.put("logurl", Utils.stringAtIndex(responseValues, 2));
            vars.put("vText8", Utils.stringAtIndex(responseValues, 3));
            vars.put("vText7", Utils.stringAtIndex(responseValues, 4));
            vars.put("vText6", Utils.stringAtIndex(responseValues, 5));
            vars.put("vText5", Utils.stringAtIndex(responseValues, 6));
            vars.put("vText4", Utils.stringAtIndex(responseValues, 7));
            vars.put("vText3", Utils.stringAtIndex(responseValues, 8));
            vars.put("vText2", Utils.stringAtIndex(responseValues, 9));
            vars.put("prevref", Utils.stringAtIndex(responseValues, 10));
            //vars.put("", Utils.stringAtIndex(responseValues, 11)); ??
            vars.put("emotionalhistory", Utils.stringAtIndex(responseValues, 12));
            vars.put("ttsLocMP3", Utils.stringAtIndex(responseValues, 13));
            vars.put("ttsLocTXT", Utils.stringAtIndex(responseValues, 14));
            vars.put("ttsLocTXT3", Utils.stringAtIndex(responseValues, 15));
            vars.put("ttsText", Utils.stringAtIndex(responseValues, 16));
            vars.put("lineRef", Utils.stringAtIndex(responseValues, 17));
            vars.put("lineURL", Utils.stringAtIndex(responseValues, 18));
            vars.put("linePOST", Utils.stringAtIndex(responseValues, 19));
            vars.put("lineChoices", Utils.stringAtIndex(responseValues, 20));
            vars.put("lineChoicesAbbrev", Utils.stringAtIndex(responseValues, 21));
            vars.put("typingData", Utils.stringAtIndex(responseValues, 22));
            vars.put("divert", Utils.stringAtIndex(responseValues, 23));
            
            ChatterBotThought responseThought = new ChatterBotThought();

            responseThought.setText(Utils.stringAtIndex(responseValues, 16));
            
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