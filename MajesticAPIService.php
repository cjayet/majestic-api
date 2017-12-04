<?php namespace cjayet\MajesticSEO;

use GuzzleHttp\Client;

class MajesticAPIService {

    private $endpoint = "https://api.majestic.com/";
    
    public function __construct($apiKey, $sandbox = false)
    {
        if($sandbox == true) {
            $this->endpoint = "https://developer.majestic.com/";
        }
        $this->responseType = "json";
        $this->apiKey = $apiKey;
    }

    public function setResponseType($type)
    {
        $this->responseType = $type;
    }

    public function executeCommand($command, $params = array())
    {
		$client = new GuzzleHttp\Client();
		
		$params["cmd"]         = $command;
        $params["app_api_key"] = $this->apiKey;
		
		return $client->request('GET', $this->endpoint ."/". $this->responseType, [
			'query' => $params
		]);
    }

    public function __call($name, $arguments)
    {
        $command = ucfirst($name);
        if(isset($arguments[1])) {
            $params  = $arguments[1];
        } else {
            $params = array();
        }

        if(is_string($arguments[0])) {
            $params['item'] = $arguments[0];
        } elseif(is_array($arguments[0])) {
            $counter = 0;
            foreach ($arguments[0] as $url) {
                $params['item' . $counter] = $url;
                $counter++;
            }
            $params['items'] = $counter;
        }
        return $this->executeCommand($command, $params);
    }
}