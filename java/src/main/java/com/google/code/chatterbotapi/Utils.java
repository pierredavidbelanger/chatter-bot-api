package com.google.code.chatterbotapi;

import java.io.BufferedReader;
import java.io.ByteArrayInputStream;
import java.io.InputStreamReader;
import java.io.OutputStreamWriter;
import java.io.Reader;
import java.io.StringWriter;
import java.math.BigInteger;
import java.net.URL;
import java.net.URLConnection;
import java.net.URLEncoder;
import java.security.MessageDigest;
import java.util.Map;
import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;
import javax.xml.xpath.XPath;
import javax.xml.xpath.XPathConstants;
import javax.xml.xpath.XPathExpression;
import javax.xml.xpath.XPathFactory;
import org.w3c.dom.Document;

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
class Utils {
    
    public static String parametersToWWWFormURLEncoded(Map<String, String> parameters) throws Exception {
        StringBuilder s = new StringBuilder();
        for (Map.Entry<String, String> parameter : parameters.entrySet()) {
            if (s.length() > 0) {
                s.append("&");
            }
            s.append(URLEncoder.encode(parameter.getKey(), "UTF-8"));
            s.append("=");
            s.append(URLEncoder.encode(parameter.getValue(), "UTF-8"));
        }
        return s.toString();
    }
    
    public static String md5(String input) throws Exception {
        MessageDigest md5 = MessageDigest.getInstance("MD5");
        md5.update(input.getBytes("UTF-8"));
        BigInteger hash = new BigInteger(1, md5.digest());
        return String.format("%1$032X", hash);
    }
    
    public static String post(String url, Map<String, String> parameters) throws Exception {
        URLConnection connection = new URL(url).openConnection();
        connection.setRequestProperty("User-Agent", "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/28.0.1500.95 Safari/537.36");
        connection.setDoOutput(true);
        connection.setDoInput(true);
        OutputStreamWriter osw = new OutputStreamWriter(connection.getOutputStream());
        osw.write(parametersToWWWFormURLEncoded(parameters));
        osw.flush();
        osw.close();
        Reader r = new BufferedReader(new InputStreamReader(connection.getInputStream()));
        StringWriter w = new StringWriter();
        char[] buffer = new char[1024];
        int n = 0;
        while ((n = r.read(buffer)) != -1) {
            w.write(buffer, 0, n);
        }
        r.close();
        return w.toString();
    }
    
    public static String xPathSearch(String input, String expression) throws Exception {
        DocumentBuilder documentBuilder = DocumentBuilderFactory.newInstance().newDocumentBuilder();
        XPath xPath = XPathFactory.newInstance().newXPath();
        XPathExpression xPathExpression = xPath.compile(expression);
        Document document = documentBuilder.parse(new ByteArrayInputStream(input.getBytes("UTF-8")));
        String output = (String) xPathExpression.evaluate(document, XPathConstants.STRING);
        return output == null ? "" : output.trim();
    }
    
    public static String stringAtIndex(String[] strings, int index) {
        if (index >= strings.length) return "";
        return strings[index];
    }
}