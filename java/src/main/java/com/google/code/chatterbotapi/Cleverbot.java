package com.google.code.chatterbotapi;

import java.util.LinkedHashMap;
import java.util.Locale;
import java.util.Map;
import org.jsoup.Jsoup;
import org.jsoup.nodes.Document;

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
    private final String baseUrl;
    private final String serviceUrl;
    private int endIndex;

    public Cleverbot(String baseUrl, String serviceUrl, int endIndex) {
        this.baseUrl = baseUrl;
        this.serviceUrl = serviceUrl;
        this.endIndex = endIndex;
    }

    @Override
    public ChatterBotSession createSession(Locale... locales) {
        return new Session(locales);
    }

    private class Session implements ChatterBotSession {
        private final Map<String, String> vars;
        private final Map<String, String> headers;
        private final Map<String, String> cookies;

        public Session(Locale... locales) {
            vars = new LinkedHashMap<String, String>();
            vars.put("start", "y");
            vars.put("icognoid", "wsf");
            vars.put("fno", "0");
            vars.put("sub", "Say");
            vars.put("islearning", "1");
            vars.put("cleanslate", "false");
            headers = new LinkedHashMap<String, String>();
            if (locales.length > 0)
                headers.put("Accept-Language", Utils.toAcceptLanguageTags(locales));
            cookies = new LinkedHashMap<String, String>();
            try {
                Utils.request(baseUrl, headers, cookies, null);
            } catch (Exception e) {
                throw new RuntimeException(e);
            }
        }
        
        public ChatterBotThought think(ChatterBotThought thought) throws Exception {
            vars.put("stimulus", thought.getText());

            String formData = Utils.parametersToWWWFormURLEncoded(vars);
            String formDataToDigest = formData.substring(9, endIndex);
            String formDataDigest = Utils.md5(formDataToDigest);
            vars.put("icognocheck", formDataDigest);

            String response = Utils.request(serviceUrl, headers, cookies, vars);
            
            Document doc = Jsoup.parse(response);
            
            vars.put("vText8", doc.select("[name=vText8]").val());
            vars.put("vText7", doc.select("[name=vText7]").val());
            vars.put("vText6", doc.select("[name=vText6]").val());
            vars.put("vText5", doc.select("[name=vText5]").val());
            vars.put("vText4", doc.select("[name=vText4]").val());
            vars.put("vText3", doc.select("[name=vText3]").val());
            vars.put("vText2", doc.select("[name=vText2]").val());
            vars.put("prevref", doc.select("[name=prevref]").val());
            vars.put("lineRef", doc.select("[name=lineRef]").val());
            
            ChatterBotThought responseThought = new ChatterBotThought();
            
            responseThought.setText(vars.get("vText2"));
            
            return responseThought;
        }

        public String think(String text) throws Exception {
            ChatterBotThought thought = new ChatterBotThought();
            thought.setText(text);
            return think(thought).getText();
        }
    }
}