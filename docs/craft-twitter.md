# craft.twitter

## get(uri, query, headers, options, enableCache, cacheExpire)
Performs a GET request on Twitter API and returns the response.

### Arguments
- **`uri`** – The API request URI.
- **`query`** – The query parameters as an array.
- **`headers`** – The request headers as an array.
- **`options`** – The request options as an array.
- **`enableCache`** – Boolean for enabling/disabling cache. Defaults to `false`.
- **`cacheExpire`** – Cache expiry in seconds. 0 means never expires. Defaults to `0`.

### Return Values
Returns the API response as an array.

## getTweetById(tweetId, query)
Get a tweet by its ID.

```twig
{% set tweetId = 998619839043256321 %}
{% set tweet = craft.twitter.getTweetById(tweetId) %}

{% if tweet %}
    <div class="tweet">
        <img src="{{ tweet.getUserProfileImageUrl() }}" />
        <p><cite><a href="{{ tweet.url }}">{{ tweet.username }} (@{{ tweet.userScreenName }})</a></cite></p>
        <blockquote>{{ tweet.text|autoLinkTweet }}</blockquote>
    </div>
{% endif %}
```

### Arguments
- **`tweetId`** – Tweet ID
- **`query`** – An array of query parameters that can be added to the API request. Defaults to `[]`.

### Return Values
Returns a [Tweet model](tweet-model.md) or `null`.

## getTweetByUrl(urlOrId, query)
Get a tweet by its URL.

```twig
{% set tweetUrl = 'https://twitter.com/CraftCMS/status/998619839043256321' %}
{% set tweet = craft.twitter.getTweetByUrl(tweetUrl) %}

{% if tweet %}
    <div class="tweet">
        <img src="{{ tweet.getUserProfileImageUrl() }}" />
        <p><cite><a href="{{ tweet.url }}">{{ tweet.username }} (@{{ tweet.userScreenName }})</a></cite></p>
        <blockquote>{{ tweet.text|autoLinkTweet }}</blockquote>
    </div>
{% endif %}
```

### Arguments
- **`urlOrId`** – The URL or ID of the tweet.
- **`query`** – An array of query parameters that can be added to the API request. Defaults to `[]`.

### Return Values
Returns a [Tweet model](tweet-model.md) or `null`.

## getUserById(twitterUserId, query)
Get a Twitter user by its ID.

```twig
{% set twitterUser = craft.twitter.getUserById(236658433) %}

{% if twitterUser %}
    <ul>
        <li>{{ twitterUser.name }} (@{{ twitterUser.screen_name }})</li>
        <li>{{ twitterUser.location }}</li>
        <li>{{ twitterUser.description }}</li>
    </ul>
{% endif %}
```

### Arguments
- **`twitterUserId`** – Twitter user ID.
- **`query`** – An array of query parameters that can be added to the API request. Defaults to `[]`.

### Return Values
Returns a user as an array, or `null`.

## getUserProfileImageResourceUrl(twitterUserId, size)
Get a user profile image from a user ID.

### Arguments
- **`twitterUserId`** – Twitter user ID.
- **`size`** – Size of the image as an integer. Defaults to `48`.

### Return Values
Returns the user profile image resource URL, or `null`.
