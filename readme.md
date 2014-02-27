# sprinker PHP client

PHP client for [sprinkler](https://github.com/FelixOnline/sprinkler)

## Usage

```
$sprinkler = new Sprinkler(URL, [ADMIN_KEY]);

$channel = $sprinkler->channel(NAME, [CHANNEL_KEY]);
$channel->post(MESSAGE);
```