<?php
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
    
    #################################################
    # API
    #################################################
    
    class ChatterBotType
    {
        const CLEVERBOT = 1;
        const JABBERWACKY = 2;
        const PANDORABOTS = 3;
    }
    
    class ChatterBotFactory
    {
        public function create($type, $arg = null)
        {
            switch ($type)
            {
                case ChatterBotType::CLEVERBOT:
                {
                    return new _Cleverbot('http://www.cleverbot.com/webservicemin', 26);
                }
                case ChatterBotType::JABBERWACKY:
                {
                    return new _Cleverbot('http://jabberwacky.com/webservicemin', 20);
                }
                case ChatterBotType::PANDORABOTS:
                {
                    if ($arg == null) {
                        throw new Exception('PANDORABOTS needs a botid arg');
                    }
                    return new _Pandorabots($arg);
                }
            }
        }
    }
    
    abstract class ChatterBot
    {
        public function createSession()
        {
            return null;
        }
    }
    
    abstract class ChatterBotSession
    {
        public function thinkThought($thought)
        {
            return $thought;
        }
        
        public function think($text)
        {
            $thought = new ChatterBotThought();
            $thought->setText($text);
            return $this->thinkThought($thought)->getText();
        }
    }
    
    class ChatterBotThought
    {
        private $text;
        
        public function getText()
        {
            return $this->text;
        }
        
        public function setText($text)
        {
            $this->text = $text;
        }
    }

    #################################################
    # Cleverbot impl
    #################################################
    
    class _Cleverbot extends ChatterBot
    {
        private $url;
        private $endIndex;
        
        public function __construct($url, $endIndex)
        {
            $this->url = $url;
            $this->endIndex = $endIndex;
        }
        
        public function getUrl()
        {
            return $this->url;
        }
        
        public function setUrl($url)
        {
            $this->url = $url;
        }        

        public function getEndIndex()
        {
            return $this->endIndex;
        }
        
        public function setEndIndex($endIndex)
        {
            $this->endIndex = $endIndex;
        }        

        public function createSession()
        {
            return new _CleverbotSession($this);
        }
    }
    
    class _CleverbotSession extends ChatterBotSession
    {
        private $bot;
        private $vars;

        public function __construct($bot)
        {
            $this->bot = $bot;
            $this->vars = array();
            $this->vars['start'] = 'y';
            $this->vars['icognoid'] = 'wsf';
            $this->vars['fno'] = '0';
            $this->vars['sub'] = 'Say';
            $this->vars['islearning'] = '1';
            $this->vars['cleanslate'] = 'false';
        }

        public function thinkThought($thought)
        {
            $this->vars['stimulus'] = $thought->getText();
            $data = http_build_query($this->vars);
            $dataToDigest = substr($data, 9, $this->bot->getEndIndex());
            $dataDigest = md5($dataToDigest);
            $this->vars['icognocheck'] = $dataDigest;
            $response = _utils_post($this->bot->getUrl(), $this->vars);
            $responseValues = explode("\r", $response);
            //self.vars['??'] = _utils_string_at_index($responseValues, 0);
            $this->vars['sessionid'] = _utils_string_at_index($responseValues, 1);
            $this->vars['logurl'] = _utils_string_at_index($responseValues, 2);
            $this->vars['vText8'] = _utils_string_at_index($responseValues, 3);
            $this->vars['vText7'] = _utils_string_at_index($responseValues, 4);
            $this->vars['vText6'] = _utils_string_at_index($responseValues, 5);
            $this->vars['vText5'] = _utils_string_at_index($responseValues, 6);
            $this->vars['vText4'] = _utils_string_at_index($responseValues, 7);
            $this->vars['vText3'] = _utils_string_at_index($responseValues, 8);
            $this->vars['vText2'] = _utils_string_at_index($responseValues, 9);
            $this->vars['prevref'] = _utils_string_at_index($responseValues, 10);
            //$this->vars['??'] = _utils_string_at_index($responseValues, 11);
            $this->vars['emotionalhistory'] = _utils_string_at_index($responseValues, 12);
            $this->vars['ttsLocMP3'] = _utils_string_at_index($responseValues, 13);
            $this->vars['ttsLocTXT'] = _utils_string_at_index($responseValues, 14);
            $this->vars['ttsLocTXT3'] = _utils_string_at_index($responseValues, 15);
            $this->vars['ttsText'] = _utils_string_at_index($responseValues, 16);
            $this->vars['lineRef'] = _utils_string_at_index($responseValues, 17);
            $this->vars['lineURL'] = _utils_string_at_index($responseValues, 18);
            $this->vars['linePOST'] = _utils_string_at_index($responseValues, 19);
            $this->vars['lineChoices'] = _utils_string_at_index($responseValues, 20);
            $this->vars['lineChoicesAbbrev'] = _utils_string_at_index($responseValues, 21);
            $this->vars['typingData'] = _utils_string_at_index($responseValues, 22);
            $this->vars['divert'] = _utils_string_at_index($responseValues, 23);
            $responseThought = new ChatterBotThought();
            $responseThought->setText(_utils_string_at_index($responseValues, 16));
            return $responseThought;
        }
    }
    
    #################################################
    # Pandorabots impl
    #################################################
    
    class _Pandorabots extends ChatterBot
    {
        private $botid;
        
        public function __construct($botid)
        {
            $this->botid = $botid;
        }
        
        public function getBotid()
        {
            return $this->botid;
        }
        
        public function setBotid($botid)
        {
            $this->botid = $botid;
        }        
        
        public function createSession()
        {
            return new _PandorabotsSession($this);
        }
    }
    
    class _PandorabotsSession extends ChatterBotSession
    {
        private $vars;
        
        public function __construct($bot)
        {
            $this->vars = array();
            $this->vars['botid'] = $bot->getBotid();
            $this->vars['custid'] = uniqid();
        }
        
        public function thinkThought($thought)
        {
            $this->vars['input'] = $thought->getText();
            $response = _utils_post('http://www.pandorabots.com/pandora/talk-xml', $this->vars);
            $element = new SimpleXMLElement($response);
            $result = $element->xpath('//result/that/text()');
            $responseThought = new ChatterBotThought();
            $responseThought->setText(trim($result[0][0]));
            return $responseThought;
        }
    }
    
    #################################################
    # Utils
    #################################################

    function _utils_post($url, $params)
    {
        $contextParams = array();
        $contextParams['http'] = array();
        $contextParams['http']['method'] = 'POST';
        $contextParams['http']['content'] = http_build_query($params);
        $context = stream_context_create($contextParams);
        $fp = fopen($url, 'rb', false, $context);
        $response = stream_get_contents($fp);
        fclose($fp);
        return $response;
    }
    
    function _utils_string_at_index($strings, $index)
    {
        if (count($strings) > $index)
        {
            return $strings[$index];
        }
        else
        {
            return '';
        }
    }
?>
