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

```{% set response = craft.twitter.get('statuses/user_timeline', {count:5}) %}

{% if response.success %}
    {% set tweets = response.data %}

    {% for tweet in tweets %}
        <div class="tweet">
            <img src="{{ tweet['user']['profile_image_url_https'] }}" />
            <p><cite><a href="https://twitter.com/statuses/{{ tweet['id'] }}">{{ tweet['user']['name'] }} (@{{ tweet['user']['screen_name'] }})</a></cite></p>
            <blockquote>{{ tweet.text|autoLinkTweet }}</blockquote>
        </div>
    {% endfor %}
{% else %}
    <p>An error occured.</p>
{% endif %}```

You can also use the `json_encode` filter to display all of the attributes and values the response has to offer:

```twig
{% set response = craft.twitter.get('statuses/user_timeline', {count:5}) %}

{% if response.success %}
    {% set tweets = response.data %}
    <pre>{{ tweets|json_encode(constant('JSON_PRETTY_PRINT')) }}</pre>
{% else %}
    <p>An error occured:</p>
    <pre>{{ response.data|json_encode(constant('JSON_PRETTY_PRINT')) }}</pre>
{% endif %}
```

## getTweet(urlOrId, query)
Get a tweet by its URL.

```twig
{% set tweetUrl = 'https://twitter.com/CraftCMS/status/998619839043256321' %}
{% set tweet = craft.twitter.getTweet(tweetUrl) %}

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

## parseTweetData(data)
Parses tweet data and returns a tweet model.

### Arguments
- **`data`** – Tweet’s API data as an array.

### Return Values
Returns a [Tweet model](tweet-model.md).

```twig
{% set response = craft.twitter.get("statuses/show", {id: 1004820018939514880, tweet_mode: 'extended' }) %}

{% if response.success %}
    {% set tweet = craft.twitter.parseTweetData(response.data) %}

    <div class="tweet">
        <img src="{{ tweet.getUserProfileImageUrl() }}" />
        <p><cite><a href="{{ tweet.url }}">{{ tweet.username }} (@{{ tweet.userScreenName }})</a></cite></p>
        <blockquote>{{ tweet.text|autoLinkTweet }}</blockquote>
    </div>
{% else %}
    <p>Couldn’t get tweet.</p>
{% endif %}
```
