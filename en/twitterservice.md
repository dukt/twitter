# TwitterService

## get( $uri, $params, $headers, $enableCache, $cacheExpire )

Performs a GET request on Twitter API and returns the response.

**Params:**

- `$uri` — API request URI. Required field.
- `$params` — Parameters as an array.
- `$headers` — Request headers as an array.
- `$enableCache` — Boolean for enabling/disabling cache. Default is `true`.
- `$cacheExpire` — Set cache expiry. 0 means never expire. Default is `0`.

**Returns:**

- API response.

## getTweetById( $tweetId, $params )

Returns a tweet from its ID. Add additional parameters to the API request with `$params`.

## getUserById( $userId, $params )

Returns a Twitter user from his ID. Add additional parameters to the API request with `$params`.