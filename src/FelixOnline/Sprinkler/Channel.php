<?php
namespace FelixOnline\Sprinkler;

class Channel
{
    public $sprinkler;
    public $channel;
    protected $key;

    public function __construct(\FelixOnline\Sprinkler $sprinkler, $channel, $key)
    {
        if (is_null($key)) {
            throw new \Exception('Channel key must be defined');
        }

        $this->sprinkler = $sprinkler;
        $this->channel = $channel;
        $this->key = $key;
    }

    /**
     * Post a message to the channel
     *
     * $message - string
     */
    public function post($message)
    {
        $request = $this->sprinkler->client->post('/message/' . $this->channel);
        $request->setHeader('key', $this->key);
        $request->setHeader('Content-Type', 'application/json');
        $request->setBody($message);

        try {
            $response = $request->send()->json();
        } catch (\Guzzle\Http\Exception\BadResponseException $e) {
            $json = $e->getResponse()->json();
            throw new \Exception($json['message']);
        }
    }
}
