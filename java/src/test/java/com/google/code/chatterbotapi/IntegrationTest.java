package com.google.code.chatterbotapi;

import org.junit.BeforeClass;
import org.junit.Test;

import java.util.ResourceBundle;

import static org.junit.Assert.assertNotEquals;
import static org.junit.Assert.assertNotNull;

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
public class IntegrationTest {

    private static String apiKey;

    @BeforeClass
    public static void loadApiKey() {
        apiKey = ResourceBundle.getBundle(IntegrationTest.class.getPackage().getName() + ".cleverbot").getString("api.key");
    }

    @Test
    public void testApiKeyNotNull() {
        assertNotNull("API Key should not be null", apiKey);
    }

    @Test(expected = Exception.class)
    public void testCleverbotNeedsAnAPIKey() throws Exception {
        new ChatterBotFactory().create(ChatterBotType.CLEVERBOT);
    }

    @Test
    public void testCleverbotRespond() throws Exception {

        ChatterBotFactory chatterBotFactory = new ChatterBotFactory();
        ChatterBot bot = chatterBotFactory.create(ChatterBotType.CLEVERBOT, apiKey);
        ChatterBotSession session = bot.createSession();

        String response = session.think("Hi!");
        assertNotNull("Response 1 should not be null", response);
        response = response.trim();
        System.out.println(response);
        assertNotEquals("Response 1 should not be empty", "", response);

        response = session.think("It works!");
        assertNotNull("Response 2 should not be null", response);
        response = response.trim();
        System.out.println(response);
        assertNotEquals("Response 2 should not be empty", "", response);
    }
}
