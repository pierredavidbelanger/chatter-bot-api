using System;

using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Net;
using System.Text;
using System.Text.RegularExpressions;
using System.Threading.Tasks;
using System.Xml.Linq;
using Windows.Security.Cryptography;
using Windows.Security.Cryptography.Core;
using Windows.Storage.Streams;

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
namespace ChatterBotAPI {
	
	static class Utils {
		
		public static string ParametersToWWWFormURLEncoded(IDictionary<string, string> parameters) {
			string wwwFormUrlEncoded = null;
			foreach (string parameterKey in parameters.Keys) {
				string parameterValue = parameters[parameterKey];
				string parameter = string.Format("{0}={1}", WebUtility.UrlEncode(parameterKey), WebUtility.UrlEncode(parameterValue));
				if (wwwFormUrlEncoded == null) {
					wwwFormUrlEncoded = parameter;
				} else {
					wwwFormUrlEncoded = string.Format("{0}&{1}", wwwFormUrlEncoded, parameter);
				}
			}
			return wwwFormUrlEncoded;
		}
		
		public static string MD5(string input) {
			var alg = HashAlgorithmProvider.OpenAlgorithm(HashAlgorithmNames.Md5);
			IBuffer buff = CryptographicBuffer.ConvertStringToBinary(input, BinaryStringEncoding.Utf8);
			var hashed = alg.HashData(buff);
			var res = CryptographicBuffer.EncodeToHexString(hashed);
			return res;
		}

		public async static Task<CookieCollection> GetCookies(string url) {
			CookieContainer container = new CookieContainer();
			var request = (HttpWebRequest)WebRequest.Create(url);
			request.Method = "GET";
			request.Headers["UserAgent"] = "Mozilla/5.0 (Windows NT 6.3; WOW64; rv:28.0) Gecko/20100101 Firefox/28.0;";
			request.ContentType = "text/html";
			request.CookieContainer = container;

			await request.GetResponseAsync();

			return container.GetCookies(request.RequestUri);
		}

		public async static Task<string> Post(string url, IDictionary<string, string> parameters, CookieCollection cookies) {
			string postData = ParametersToWWWFormURLEncoded(parameters);
			byte[] postDataBytes = Encoding.ASCII.GetBytes(postData);

			var webRequest = (HttpWebRequest)WebRequest.Create(url);
			if (cookies != null) {
				var container = new CookieContainer();
				container.Add(new Uri("http://www.cleverbot.com/"), cookies);
				webRequest.CookieContainer = container;
			}
			webRequest.Method = "POST";
			webRequest.ContentType = "application/x-www-form-urlencoded";
			
			Stream outputStream = await webRequest.GetRequestStreamAsync();
			outputStream.Write(postDataBytes, 0, postDataBytes.Length);
			outputStream.Dispose();

			WebResponse webResponse = await webRequest.GetResponseAsync();
			StreamReader responseStreamReader = new StreamReader(webResponse.GetResponseStream());
			return responseStreamReader.ReadToEnd().Trim();
		}
		
		public static string XPathSearch(string input, string expression) {
			var res = Regex.Match(input, "<that>(.+)<\\/that>").Groups[1].ToString();

			return res;
		}
		
		public static string StringAtIndex(string[] strings, int index) {
			if (index >= strings.Length) return "";
			return strings[index];
		}
	}
}
