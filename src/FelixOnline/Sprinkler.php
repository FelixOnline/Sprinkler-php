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
     * Get version details
     */
    public function version()
    {
        $request = $this->client->get('/');
        $return = $request->send()->json();

        unset($return['status']);

        return $return;
    }

    /**
     * Get list of channels
     */
    public function channels()
    {
        $request = $this->client->get('/channel');
        $return = $request->send()->json();

        return $return['channels'];
    }

    /**
     * Returns a channel object
     *
     * @throws Exception if channel doesn't exisit
     */
    public function channel($name, $channelKey = NULL)
    {
        // check that channel exists first
        $channels = $this->channels();
        if (!in_array("/" . $name, $channels)) {
            throw new \Exception("Channel '$name' doesn't exist");
        }

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

        $channel = new Sprinkler\Channel($this, $name, $channelKey, $this->key);
        return $channel;
    }

    /**
     * Create a new channel or if it already exisits then return the channel 
     * object
     */
    public function newChannel($name)
    {
        if (is_null($name)) {
            throw new \Exception('No admin key defined');
        }

        $channels = $this->channels();
        if (in_array("/" . $name, $channels)) {
            return $this->channel($name);
        }

        $request = $this->client->post('/channel');
        $request->setHeader('key', $this->key);
        $request->setHeader('Content-Type', 'application/json');
        $request->setBody(json_encode(array(
            'channel' => $name
        )));

        try {
            $response = $request->send()->json();
            return new Sprinkler\Channel($this, $name, $response['key'], $this->key);
        } catch (\Guzzle\Http\Exception\BadResponseException $e) {
            $json = $e->getResponse()->json();
            throw new \Exception($json['message']);
        }
    }
}
