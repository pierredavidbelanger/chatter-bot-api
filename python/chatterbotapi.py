import re
import sys
import hashlib

if sys.version_info >= (3, 0):
    from urllib.request import build_opener, HTTPCookieProcessor, urlopen
    from urllib.parse import urlencode
    import http.cookiejar as cookielib

else:
    from urllib import urlencode, urlopen
    from urllib2 import build_opener, HTTPCookieProcessor
    import cookielib

import uuid
import xml.dom.minidom

"""
    chatterbotapi
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
"""

#################################################
# API
#################################################



class ChatterBotType:

    CLEVERBOT = 1
    JABBERWACKY = 2
    PANDORABOTS = 3

class ChatterBotFactory:

    def create(self, type, arg = None):
        if type == ChatterBotType.CLEVERBOT:
            return _Cleverbot('http://www.cleverbot.com', 'http://www.cleverbot.com/webservicemin', 35)
        elif type == ChatterBotType.JABBERWACKY:
            return _Cleverbot('http://jabberwacky.com', 'http://jabberwacky.com/webservicemin', 29)
        elif type == ChatterBotType.PANDORABOTS:
            if arg == None:
                raise Exception('PANDORABOTS needs a botid arg')
            return _Pandorabots(arg)
        return None

class ChatterBot:

    def create_session(self):
        return None

class ChatterBotSession:

    def think_thought(self, thought):
        return thought

    def think(self, text):
        thought = ChatterBotThought()
        thought.text = text
        return self.think_thought(thought).text

class ChatterBotThought:

    pass

#################################################
# Cleverbot impl
#################################################

class _Cleverbot(ChatterBot):

    def __init__(self, baseUrl, serviceUrl, endIndex):
        self.baseUrl = baseUrl
        self.serviceUrl = serviceUrl
        self.endIndex = endIndex

    def create_session(self):
        return _CleverbotSession(self)

class _CleverbotSession(ChatterBotSession):

    def __init__(self, bot):
        self.bot = bot
        self.vars = {}
        self.vars['start'] = 'y'
        self.vars['icognoid'] = 'wsf'
        self.vars['fno'] = '0'
        self.vars['sub'] = 'Say'
        self.vars['islearning'] = '1'
        self.vars['cleanslate'] = 'false'
        self.cookieJar = cookielib.CookieJar()
        self.opener = build_opener(HTTPCookieProcessor(self.cookieJar))
        self.opener.open(self.bot.baseUrl)

    def think_thought(self, thought):
        self.vars['stimulus'] = thought.text
        data = urlencode(self.vars)
        data_to_digest = data[9:self.bot.endIndex]
        data_digest = hashlib.md5(data_to_digest).hexdigest()
        data = data + '&icognocheck=' + data_digest
        url_response = self.opener.open(self.bot.serviceUrl, data)
        response = url_response.read()
        response_values = re.split(r'\\r|\r', response)
        #self.vars['??'] = _utils_string_at_index(response_values, 0)
        self.vars['sessionid'] = _utils_string_at_index(response_values, 1)
        self.vars['logurl'] = _utils_string_at_index(response_values, 2)
        self.vars['vText8'] = _utils_string_at_index(response_values, 3)
        self.vars['vText7'] = _utils_string_at_index(response_values, 4)
        self.vars['vText6'] = _utils_string_at_index(response_values, 5)
        self.vars['vText5'] = _utils_string_at_index(response_values, 6)
        self.vars['vText4'] = _utils_string_at_index(response_values, 7)
        self.vars['vText3'] = _utils_string_at_index(response_values, 8)
        self.vars['vText2'] = _utils_string_at_index(response_values, 9)
        self.vars['prevref'] = _utils_string_at_index(response_values, 10)
        #self.vars['??'] = _utils_string_at_index(response_values, 11)
        self.vars['emotionalhistory'] = _utils_string_at_index(response_values, 12)
        self.vars['ttsLocMP3'] = _utils_string_at_index(response_values, 13)
        self.vars['ttsLocTXT'] = _utils_string_at_index(response_values, 14)
        self.vars['ttsLocTXT3'] = _utils_string_at_index(response_values, 15)
        self.vars['ttsText'] = _utils_string_at_index(response_values, 16)
        self.vars['lineRef'] = _utils_string_at_index(response_values, 17)
        self.vars['lineURL'] = _utils_string_at_index(response_values, 18)
        self.vars['linePOST'] = _utils_string_at_index(response_values, 19)
        self.vars['lineChoices'] = _utils_string_at_index(response_values, 20)
        self.vars['lineChoicesAbbrev'] = _utils_string_at_index(response_values, 21)
        self.vars['typingData'] = _utils_string_at_index(response_values, 22)
        self.vars['divert'] = _utils_string_at_index(response_values, 23)
        response_thought = ChatterBotThought()
        response_thought.text = _utils_string_at_index(response_values, 16)
        return response_thought

#################################################
# Pandorabots impl
#################################################

class _Pandorabots(ChatterBot):

    def __init__(self, botid):
        self.botid = botid

    def create_session(self):
        return _PandorabotsSession(self)

class _PandorabotsSession(ChatterBotSession):

    def __init__(self, bot):
        self.vars = {}
        self.vars['botid'] = bot.botid
        self.vars['custid'] = uuid.uuid1()

    def think_thought(self, thought):
        self.vars['input'] = thought.text
        data = urlencode(self.vars)
        url_response = urlopen('http://www.pandorabots.com/pandora/talk-xml', data)
        response = url_response.read()
        response_dom = xml.dom.minidom.parseString(response)
        response_thought = ChatterBotThought()
        that_elements = response_dom.getElementsByTagName('that')
        if that_elements is None or len(that_elements) == 0 or that_elements[0] is None:
            return ''
        that_elements_child_nodes = that_elements[0].childNodes
        if that_elements_child_nodes is None or len(that_elements_child_nodes) == 0 or that_elements_child_nodes[0] is None:
            return ''
        that_elements_child_nodes_data = that_elements_child_nodes[0].data
        if that_elements_child_nodes_data is None:
            return ''
        response_thought.text = that_elements_child_nodes_data.strip()
        return response_thought

#################################################
# Utils
#################################################

def _utils_string_at_index(strings, index):
    if len(strings) > index:
        return strings[index]
    else:
        return ''



    CLEVERBOT = 1
    JABBERWACKY = 2
    PANDORABOTS = 3

class ChatterBotFactory:

    def create(self, type, arg = None):
        if type == ChatterBotType.CLEVERBOT:
            return _Cleverbot('http://www.cleverbot.com', 'http://www.cleverbot.com/webservicemin', 35)
        elif type == ChatterBotType.JABBERWACKY:
            return _Cleverbot('http://jabberwacky.com', 'http://jabberwacky.com/webservicemin', 29)
        elif type == ChatterBotType.PANDORABOTS:
            if arg == None:
                raise Exception('PANDORABOTS needs a botid arg')
            return _Pandorabots(arg)
        return None

class ChatterBot:

    def create_session(self):
        return None

class ChatterBotSession:

    def think_thought(self, thought):
        return thought

    def think(self, text):
        thought = ChatterBotThought()
        thought.text = text
        return self.think_thought(thought).text

class ChatterBotThought:

    pass

#################################################
# Cleverbot impl
#################################################

class _Cleverbot(ChatterBot):

    def __init__(self, baseUrl, serviceUrl, endIndex):
        self.baseUrl = baseUrl
        self.serviceUrl = serviceUrl
        self.endIndex = endIndex

    def create_session(self):
        return _CleverbotSession(self)

class _CleverbotSession(ChatterBotSession):

    def __init__(self, bot):
        self.bot = bot
        self.vars = {}
        self.vars['start'] = 'y'
        self.vars['icognoid'] = 'wsf'
        self.vars['fno'] = '0'
        self.vars['sub'] = 'Say'
        self.vars['islearning'] = '1'
        self.vars['cleanslate'] = 'false'
        self.cookieJar = cookielib.CookieJar()
        self.opener = build_opener(HTTPCookieProcessor(self.cookieJar))
        self.opener.open(self.bot.baseUrl)

    def think_thought(self, thought):
        self.vars['stimulus'] = thought.text
        data = urlencode(self.vars)
        data_to_digest = data[9:self.bot.endIndex]
        data_digest = hashlib.md5(data_to_digest.encode('utf-8')).hexdigest()
        data = data + '&icognocheck=' + data_digest
        url_response = self.opener.open(self.bot.serviceUrl, data.encode('utf-8'))
        response = str(url_response.read())
        response_values = re.split(r'\\r|\r', response)
        #self.vars['??'] = _utils_string_at_index(response_values, 0)
        self.vars['sessionid'] = _utils_string_at_index(response_values, 1)
        self.vars['logurl'] = _utils_string_at_index(response_values, 2)
        self.vars['vText8'] = _utils_string_at_index(response_values, 3)
        self.vars['vText7'] = _utils_string_at_index(response_values, 4)
        self.vars['vText6'] = _utils_string_at_index(response_values, 5)
        self.vars['vText5'] = _utils_string_at_index(response_values, 6)
        self.vars['vText4'] = _utils_string_at_index(response_values, 7)
        self.vars['vText3'] = _utils_string_at_index(response_values, 8)
        self.vars['vText2'] = _utils_string_at_index(response_values, 9)
        self.vars['prevref'] = _utils_string_at_index(response_values, 10)
        #self.vars['??'] = _utils_string_at_index(response_values, 11)
        self.vars['emotionalhistory'] = _utils_string_at_index(response_values, 12)
        self.vars['ttsLocMP3'] = _utils_string_at_index(response_values, 13)
        self.vars['ttsLocTXT'] = _utils_string_at_index(response_values, 14)
        self.vars['ttsLocTXT3'] = _utils_string_at_index(response_values, 15)
        self.vars['ttsText'] = _utils_string_at_index(response_values, 16)
        self.vars['lineRef'] = _utils_string_at_index(response_values, 17)
        self.vars['lineURL'] = _utils_string_at_index(response_values, 18)
        self.vars['linePOST'] = _utils_string_at_index(response_values, 19)
        self.vars['lineChoices'] = _utils_string_at_index(response_values, 20)
        self.vars['lineChoicesAbbrev'] = _utils_string_at_index(response_values, 21)
        self.vars['typingData'] = _utils_string_at_index(response_values, 22)
        self.vars['divert'] = _utils_string_at_index(response_values, 23)
        response_thought = ChatterBotThought()
        response_thought.text = _utils_string_at_index(response_values, 16)
        return response_thought

#################################################
# Pandorabots impl
#################################################

class _Pandorabots(ChatterBot):

    def __init__(self, botid):
        self.botid = botid

    def create_session(self):
        return _PandorabotsSession(self)

class _PandorabotsSession(ChatterBotSession):

    def __init__(self, bot):
        self.vars = {}
        self.vars['botid'] = bot.botid
        self.vars['custid'] = uuid.uuid1()

    def think_thought(self, thought):
        self.vars['input'] = thought.text
        data = urlencode(self.vars)
        url_response = urlopen('http://www.pandorabots.com/pandora/talk-xml', data)
        response = url_response.read()
        response_dom = xml.dom.minidom.parseString(response)
        response_thought = ChatterBotThought()
        that_elements = response_dom.getElementsByTagName('that')
        if that_elements is None or len(that_elements) == 0 or that_elements[0] is None:
            return ''
        that_elements_child_nodes = that_elements[0].childNodes
        if that_elements_child_nodes is None or len(that_elements_child_nodes) == 0 or that_elements_child_nodes[0] is None:
            return ''
        that_elements_child_nodes_data = that_elements_child_nodes[0].data
        if that_elements_child_nodes_data is None:
            return ''
        response_thought.text = that_elements_child_nodes_data.strip()
        return response_thought

#################################################
# Utils
#################################################

def _utils_string_at_index(strings, index):
    if len(strings) > index:
        return strings[index]
    else:
        return ''