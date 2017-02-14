package com.google.code.chatterbotapi;

import com.fasterxml.jackson.annotation.JsonIgnoreProperties;
import com.fasterxml.jackson.annotation.JsonProperty;
import com.fasterxml.jackson.databind.ObjectMapper;
import okhttp3.HttpUrl;
import okhttp3.OkHttpClient;
import okhttp3.Request;
import okhttp3.Response;

import java.io.InputStream;
import java.util.Locale;

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

    private final ObjectMapper objectMapper;
    private final HttpUrl baseUrl;
    private final OkHttpClient httpClient;
    private final String apiKey;

    public Cleverbot(OkHttpClient httpClient, String apiKey) {
        objectMapper = new ObjectMapper();
        baseUrl = HttpUrl.parse("https://www.cleverbot.com/getreply")
                .newBuilder().addQueryParameter("key", apiKey).build();
        this.httpClient = httpClient;
        this.apiKey = apiKey;
    }

    @Override
    public ChatterBotSession createSession(Locale... locales) {
        return new Session(locales);
    }

    private class Session implements ChatterBotSession {

        private final Locale[] locales;

        private Reply lastReply;

        public Session(Locale... locales) {
            this.locales = locales;
        }

        public ChatterBotThought think(ChatterBotThought thought) throws Exception {

            HttpUrl.Builder urlBuilder = baseUrl.newBuilder();

            String input = thought.getText();
            if (input != null) {
                urlBuilder.setQueryParameter("input", input);
            }

            Reply lastReply = this.lastReply;
            if (lastReply != null && lastReply.getConversationState() != null) {
                urlBuilder.setQueryParameter("cs", lastReply.getConversationState());
            }

            HttpUrl url = urlBuilder.build();

            Request request = new Request.Builder().get().url(url).build();

            Response response = httpClient.newCall(request).execute();
            if (!response.isSuccessful())
                throw new Exception("Unable to call Cleverbot: " + response.message());

            Reply reply;
            InputStream bodyStream = response.body().byteStream();
            try {
                //noinspection unchecked
                reply = objectMapper.readValue(bodyStream, Reply.class);
            } finally {
                bodyStream.close();
            }

            this.lastReply = reply;

            ChatterBotThought responseThought = new ChatterBotThought();
            responseThought.setText(reply.getOutput());
            return responseThought;
        }

        public String think(String text) throws Exception {
            ChatterBotThought thought = new ChatterBotThought();
            thought.setText(text);
            return think(thought).getText();
        }
    }

    @JsonIgnoreProperties(ignoreUnknown = true)
    public static class Reply {

        @JsonProperty("cs")
        private String conversationState;

        @JsonProperty("output")
        private String output;

        public String getConversationState() {
            return conversationState;
        }

        public void setConversationState(String conversationState) {
            this.conversationState = conversationState;
        }

        public String getOutput() {
            return output;
        }

        public void setOutput(String output) {
            this.output = output;
        }
    }
}