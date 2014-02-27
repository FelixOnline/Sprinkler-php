<?php
namespace FelixOnline;

class Sprinkler
{
    public $client;
    protected $key;

    public function __construct($url, $key = NULL)
    {
        // if passed a string then instanciate Guzzle
        if (is_string($url)) {
            $this->client = new \Guzzle\Http\Client($url);
        } else if(is_a($url, '\Guzzle\Http\Client'))  {
            $this->client = $url;
        } else {
            throw new \Exception('Url is not valid');
        }

        $this->key = $key;
    }

    /**
     * Returns a channel object
     */
    public function channel($name, $channelKey = NULL)
    {
        // if no channel key is specified and there is an admin key then get the 
        // channel key through the api

        if (is_null($channelKey) && !is_null($this->key)) {
            $request = $this->client->get('/channel/' . $name);
            $request->setHeader('key', $this->key);

            try {
                $response = $request->send()->json();
                $channelKey = $response['key'];
            } catch (\Guzzle\Http\Exception\BadResponseException $e) {
                $json = $e->getResponse()->json();
                throw new \Exception($json['message']);
            }
        }

        $channel = new Sprinkler\Channel($this, $name, $channelKey);
        return $channel;
    }
}
