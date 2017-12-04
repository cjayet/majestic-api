<?php
namespace Optimizme\MajesticSEO;

class MajesticAPIService
{

    private $endpoint = "https://api.majestic.com/api/";

    public function __construct($apiKey, $sandbox = false)
    {
        if ($sandbox == true) {
            $this->endpoint = "https://developer.majestic.com/api/";
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
        $client = new \GuzzleHttp\Client();

        return $client->request('GET', $this->endpoint . '/' . $this->responseType . '?app_api_key=' . $this->apiKey . '&cmd=' . $command . '&item=majestic.com&Count=5&datasource=fresh', [
            'query' => $params
        ]);

    }

    public function __call($name, $arguments)
    {
        $command = ucfirst($name);
        if (isset($arguments[1])) {
            $params = $arguments[1];
        } else {
            $params = array();
        }

        if (is_string($arguments[0])) {
            $params['item'] = $arguments[0];
        } elseif (is_array($arguments[0])) {
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