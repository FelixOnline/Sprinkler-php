# sprinker PHP client

PHP client for [sprinkler](https://github.com/FelixOnline/sprinkler)

## Usage

Post to a channel:

```php
$sprinkler = new \FelixOnline\Sprinkler(URL, [ADMIN_KEY]);

$channel = $sprinkler->channel(NAME, [CHANNEL_KEY]);
$channel->post(json_encode(array(
    'message' => 'Hello World',
)));
```

*If you have an admin key then the channel key is optional.*

**Other methods**

```php
$sprinkler->channels(); // get an array of available channels

$channel = $sprinkler->newChannel(CHANNEL_NAME); // create new channel [requires admin key]
```
