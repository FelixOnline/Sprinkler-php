<?php
namespace FelixOnline\Sprinkler;

class Channel
{
    public $sprinkler;
    public $channel;
    protected $key;
    protected $adminKey;
    protected $deleted;

    public function __construct(\FelixOnline\Sprinkler $sprinkler, $channel, $key, $adminKey)
    {
        if (is_null($key)) {
            throw new \Exception('Channel key must be defined');
        }

        $this->sprinkler = $sprinkler;
        $this->channel = $channel;
        $this->key = $key;
        $this->adminKey = $adminKey;

        $this->deleted = false;
    }

    /**
     * Delete the channel
     *
     * @throws Exception if channel has been deleted
     */
    public function delete()
    {
        if($this->deleted) {
            throw new \Exception("This channel is no longer valid");
        }

        $request = $this->sprinkler->client->delete('/channel/' . $this->channel);
        $request->setHeader('key', $this->adminKey);

        try {
            $request->send();

            $this->deleted = true;
        } catch (\Guzzle\Http\Exception\BadResponseException $e) {
            $json = $e->getResponse()->json();
            throw new \Exception($json['message']);
        }
    }

    /**
     * Get a new channel key
     *
     * @throws Exception if channel has been deleted
     */
    public function resetKey()
    {
        if($this->deleted) {
            throw new \Exception("This channel is no longer valid");
        }

        $request = $this->sprinkler->client->post('/channel/' . $this->channel);
        $request->setHeader('key', $this->adminKey);

        try {
            $response = $request->send()->json();

            $this->key = $response['key'];
        } catch (\Guzzle\Http\Exception\BadResponseException $e) {
            $json = $e->getResponse()->json();
            throw new \Exception($json['message']);
        }
    }

    /**
     * Get the number of listeners
     *
     * @throws Exception if channel has been deleted
     */
    public function getListeners()
    {
        if($this->deleted) {
            throw new \Exception("This channel is no longer valid");
        }

        $request = $this->sprinkler->client->get('/channel/' . $this->channel);
        $request->setHeader('key', $this->adminKey);

        try {
            $response = $request->send()->json();

            return $response['listeners'];
        } catch (\Guzzle\Http\Exception\BadResponseException $e) {
            $json = $e->getResponse()->json();
            throw new \Exception($json['message']);
        }
    }

    /**
     * Post a message to the channel
     *
     * $message - string
     *
     * @throws Exception if channel has been deleted
     */
    public function post($message)
    {
        if($this->deleted) {
            throw new \Exception("This channel is no longer valid");
        }

        $request = $this->sprinkler->client->post('/message/' . $this->channel);
        $request->setHeader('key', $this->key);
        $request->setHeader('Content-Type', 'application/json');
        $request->setBody($message);

        try {
            $response = $request->send()->json();

            return $response['listeners'];
        } catch (\Guzzle\Http\Exception\BadResponseException $e) {
            $json = $e->getResponse()->json();
            throw new \Exception($json['message']);
        }
    }
}
