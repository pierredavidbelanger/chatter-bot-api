package com.google.code.chatterbotapi;
import java.util.Scanner;

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

public class ChatBotMeetsChatbot
{
    public static void main(String[] args) throws Exception
    {
        String s;
        String pandorabotBotID = "b0dafd24ee35a477";
        int botChoiceOne;
        int botChoiceTwo;
        int conversationLoopNum;
        String botOneType = "CLEVERBOT";
        String botTwoType = "CLEVERBOT";
        Scanner input = new Scanner(System.in);
        ChatterBotFactory factory = new ChatterBotFactory();

        System.out.println("Hello and welcome to chatbot meets chatbot.");
        
        System.out.print("What phrase would you like the first bot to start the conversation with?\n>");
        s = input.nextLine();
        
        System.out.print("Select the first chatbot type (enter a number)\n1: Cleverbot\n2: Jabberwacky\n3: Pandorabots\n>");
        botChoiceOne = input.nextInt();
        
        System.out.print("Select the second chatbot type (enter a number)\n1: Cleverbot\n2: Jabberwacky\n3: Pandorabots\n>");
        botChoiceTwo = input.nextInt();
        
        System.out.print("How many times will you like to cycle through the exchange of words between the bots?\nCan be any integer (<1 is infinite and will require CTR + C)\n>");
        conversationLoopNum = input.nextInt();
        
        switch(botChoiceOne)    //The first bot choice will be converted into a string later to be converted to enum value
        {
            case 1: botOneType = "CLEVERBOT";
            break;
            case 2: botOneType = "JABBERWACKY";
            break;
            case 3: botOneType = "PANDORABOTS";
            break;
        }

        switch(botChoiceTwo)    //The second bot choice will be converted into a string later to be converted to enum value
        {
            case 1: botTwoType = "CLEVERBOT";
            break;
            case 2: botTwoType = "JABBERWACKY";
            break;
            case 3: botTwoType = "PANDORABOTS";
            break;
        }
        
        
        ChatterBot bot1 = factory.create(ChatterBotType.valueOf(botOneType), pandorabotBotID);  //botOneType will be converted to proper enum value (found in ChatterBotType.java)
        ChatterBotSession bot1session = bot1.createSession();

        ChatterBot bot2 = factory.create(ChatterBotType.valueOf(botTwoType), pandorabotBotID);  //botTwoType will be converted to proper enum value (found in ChatterBotType.java)
        ChatterBotSession bot2session = bot2.createSession();
        
        /*
         * This would be so much simpler if I had no option for infinite running.
         * The for loop below handles the conversation between the two bots.
         */

        for (int leftoverLoops = conversationLoopNum < 1 ? 0 : conversationLoopNum; leftoverLoops != 1; leftoverLoops--)
        {
            System.out.println("bot1> " + s);

            s = bot2session.think(s);
            System.out.println("bot2> " + s);

            s = bot1session.think(s);
        }

        /*
         * Note: leftoverLoops is initialized using a ternary conditional statement.
         * This is so that it can be used in a for loop. It is equivalent to the following if/else statement:
         *
         * if (conversationLoopNum < 1)
         * {
         *      leftoverLoops = 0;
         * }
         * else
         * {
         *      leftoverLoops = conversationLoopNum;
         * }
         */
    }
}
