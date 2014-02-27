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

    }
}