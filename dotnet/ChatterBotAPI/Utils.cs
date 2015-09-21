using System.Collections.Generic;
using System.IO;
using System.Net;
using System.Text;
using System.Xml.XPath;

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
    internal static class Utils
    {
        public static string ParametersToWWWFormURLEncoded(IDictionary<string, string> parameters)
        {
            string wwwFormUrlEncoded = null;
            foreach (var parameterKey in parameters.Keys)
            {
                var parameterValue = parameters[parameterKey];
                var parameter = string.Format("{0}={1}", System.Uri.EscapeDataString(parameterKey), System.Uri.EscapeDataString(parameterValue)); 
                if (wwwFormUrlEncoded == null)
                {
                    wwwFormUrlEncoded = parameter;
                }
                else
                {
                    wwwFormUrlEncoded = string.Format("{0}&{1}", wwwFormUrlEncoded, parameter);
                }
            }
            return wwwFormUrlEncoded;
        }

        public static string MD5(string input)
        {
            // step 1, calculate MD5 hash from input
            var md5 = System.Security.Cryptography.MD5.Create();
            var inputBytes = Encoding.ASCII.GetBytes(input);
            var hash = md5.ComputeHash(inputBytes);

            // step 2, convert byte array to hex string
            var sb = new StringBuilder();
            for (var i = 0; i < hash.Length; i++)
            {
                sb.Append(hash[i].ToString("X2"));
            }
            return sb.ToString();
        
        }

        public static CookieCollection GetCookies(string url)
        {
            CookieContainer container = new CookieContainer();
            var request = (HttpWebRequest)WebRequest.Create(url);
            request.Method = "GET";
            request.Headers.Add("UserAgent", "Mozilla/5.0 (Windows NT 6.3; WOW64; rv:28.0) Gecko/20100101 Firefox/28.0;");
            request.ContentType = "text/html";
            request.CookieContainer = container;

			var response = (HttpWebResponse)request.GetResponse();
			using (var responseStreamReader = new StreamReader(response.GetResponseStream()))
			{
				responseStreamReader.ReadToEnd ();
			}

            return container.GetCookies(request.RequestUri);
        }

        public static string Post(string url, IDictionary<string, string> parameters, CookieCollection cookies)
        {
            var postData = ParametersToWWWFormURLEncoded(parameters);
            var postDataBytes = Encoding.ASCII.GetBytes(postData);

            var request = (HttpWebRequest)WebRequest.Create(url);

            if (cookies != null)
            {
                var container = new CookieContainer();
                container.Add(cookies);
                request.CookieContainer = container;
            }

            
            request.Method = "POST";
            request.ContentType = "application/x-www-form-urlencoded";
            request.ContentLength = postDataBytes.Length;

			using (var outputStream = request.GetRequestStream())
			{
				outputStream.Write(postDataBytes, 0, postDataBytes.Length);
				outputStream.Close();

				var response = (HttpWebResponse)request.GetResponse();
				using (var responseStreamReader = new StreamReader(response.GetResponseStream()))
				{
					return responseStreamReader.ReadToEnd().Trim();
				}
			}
        }

        public static string XPathSearch(string input, string expression)
        {
            var document = new XPathDocument(new MemoryStream(Encoding.ASCII.GetBytes(input)));
            var navigator = document.CreateNavigator();
            return navigator.SelectSingleNode(expression).Value.Trim();
        }

        public static string StringAtIndex(string[] strings, int index)
        {
            if (index >= strings.Length) return "";
            return strings[index];
        }
    }
}