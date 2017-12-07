<?php
namespace Optimizme\MajesticSEO;

class MajesticAPIService
{
    private $endpoint ;

    /**
     * MajesticAPIService constructor.
     * @param $apiKey
     * @param bool $develop
     */
    public function __construct($apiKey, $develop = false)
    {
        if ($develop == true) {
            $this->endpoint = 'https://developer.majestic.com/api';
        } else {
            $this->endpoint = 'https://api.majestic.com/api';
        }
        $this->responseType = "json";
        $this->apiKey = $apiKey;
    }

    /**
     * @param $command
     * @param array $params
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function executeCommand($command, $params = array())
    {
        $client = new \GuzzleHttp\Client();
        $url = $this->endpoint .'/'. $this->responseType;

        $params['cmd'] = $command;
        $params['app_api_key'] = $this->apiKey;

        return $client->request('GET', $url, [
            'query' => $params
        ]);

    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
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