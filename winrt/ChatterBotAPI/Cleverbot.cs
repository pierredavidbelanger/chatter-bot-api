using System.Collections.Generic;
using System.Net;

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

namespace ChatterBotAPI
{
    internal class Cleverbot : ChatterBot
    {
        private readonly int endIndex;
        private readonly string baseUrl;
        private readonly string url;

        public Cleverbot(string baseUrl, string url, int endIndex)
        {
            this.baseUrl = baseUrl;
            this.url = url;
            this.endIndex = endIndex;
        }

        public ChatterBotSession CreateSession()
        {
            return new CleverbotSession(baseUrl, url, endIndex);
        }
    }

    internal class CleverbotSession : ChatterBotSession
    {
        private readonly int endIndex;
        private readonly string url;
        private readonly IDictionary<string, string> vars;
		private readonly CookieCollection cookies;

        public CleverbotSession(string baseUrl, string url, int endIndex)
        {
            this.url = url;
            this.endIndex = endIndex;
            vars = new Dictionary<string, string>();
            vars["start"] = "y";
            vars["icognoid"] = "wsf";
            vars["fno"] = "0";
            vars["sub"] = "Say";
            vars["islearning"] = "1";
            vars["cleanslate"] = "false";
			cookies = Utils.GetCookies(baseUrl);
        }

        public ChatterBotThought Think(ChatterBotThought thought)
        {
            vars["stimulus"] = thought.Text;

            var formData = Utils.ParametersToWWWFormURLEncoded(vars);
            var formDataToDigest = formData.Substring(9, endIndex);
            var formDataDigest = Utils.MD5(formDataToDigest);
            vars["icognocheck"] = formDataDigest;

            var response = Utils.Post(url, vars, cookies);

            var responseValues = response.Split('\r');

            //vars[""] = Utils.StringAtIndex(responseValues, 0); ??
            vars["sessionid"] = Utils.StringAtIndex(responseValues, 1);
            vars["logurl"] = Utils.StringAtIndex(responseValues, 2);
            vars["vText8"] = Utils.StringAtIndex(responseValues, 3);
            vars["vText7"] = Utils.StringAtIndex(responseValues, 4);
            vars["vText6"] = Utils.StringAtIndex(responseValues, 5);
            vars["vText5"] = Utils.StringAtIndex(responseValues, 6);
            vars["vText4"] = Utils.StringAtIndex(responseValues, 7);
            vars["vText3"] = Utils.StringAtIndex(responseValues, 8);
            vars["vText2"] = Utils.StringAtIndex(responseValues, 9);
            vars["prevref"] = Utils.StringAtIndex(responseValues, 10);
            //vars[""] = Utils.StringAtIndex(responseValues, 11); ??
            vars["emotionalhistory"] = Utils.StringAtIndex(responseValues, 12);
            vars["ttsLocMP3"] = Utils.StringAtIndex(responseValues, 13);
            vars["ttsLocTXT"] = Utils.StringAtIndex(responseValues, 14);
            vars["ttsLocTXT3"] = Utils.StringAtIndex(responseValues, 15);
            vars["ttsText"] = Utils.StringAtIndex(responseValues, 16);
            vars["lineRef"] = Utils.StringAtIndex(responseValues, 17);
            vars["lineURL"] = Utils.StringAtIndex(responseValues, 18);
            vars["linePOST"] = Utils.StringAtIndex(responseValues, 19);
            vars["lineChoices"] = Utils.StringAtIndex(responseValues, 20);
            vars["lineChoicesAbbrev"] = Utils.StringAtIndex(responseValues, 21);
            vars["typingData"] = Utils.StringAtIndex(responseValues, 22);
            vars["divert"] = Utils.StringAtIndex(responseValues, 23);

            var responseThought = new ChatterBotThought();

            responseThought.Text = Utils.StringAtIndex(responseValues, 16);

            return responseThought;
        }

        public string Think(string text)
        {
            return Think(new ChatterBotThought {Text = text}).Text;
        }
    }
}