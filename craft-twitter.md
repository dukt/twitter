# craft.twitter

## get( uri, query, headers, options, enableCache, cacheExpire )

Performs a GET request on Twitter API and returns the response.

**Params:**

- `uri` _(Required)_ — API request URI. Required field.
- `query` _(Optional)_ — Query parameters as an array.
- `headers` _(Optional)_ — Request headers as an array.
- `options` _(Optional)_ — Request options as an array.
- `enableCache` _(Optional)_ — Boolean for enabling/disabling cache. Default is `false`.
- `cacheExpire` _(Optional)_ — Set cache expiry. 0 means never expire. Default is `0`.

**Returns:**

- The API response.

## getTweetById( tweetId, query = [] )

Returns a tweet by its ID. Add query parameters to the API request with `query`.

## getUserById( twitterUserId, query = [] )

Returns a Twitter user by its ID. Add query parameters to the API request with `query`.

## getUserProfileImageResourceUrl( twitterUserId, size = 48 )

Returns a user image from a user ID. Default `size` is 48.