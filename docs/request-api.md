# Requesting the Twitter API

Twitter plugin allows you to perform requests on the Twitter API from inside templates.

## Performing GET requests
Sends a GET request and returns the API response. You can use any GET request listed in [Twitter REST API](https://dev.twitter.com/docs/api/1.1).

```twig
{% set response = craft.twitter.get(uri) %}
```

## Displaying Tweets
This example displays recent tweets by calling `statuses/user_timeline` API method:

```twig
{% set tweets = craft.twitter.get('statuses/user_timeline', {count:5}) %}

{% if response.success %}
    {% set tweets = response.data %}
    <pre>{{ tweets|json_encode(constant('JSON_PRETTY_PRINT')) }}</pre>
{% else %}
    <p>An error occured:</p>
    <pre>{{ response|json_encode(constant('JSON_PRETTY_PRINT')) }}</pre>
{% endif %}
```

## Caching API Responses
When requesting the API, it is recommended to cache responses in order to reduce api calls and server load.

```twig
{% cache for 1 day %}
    {% set tweets = craft.twitter.get('statuses/user_timeline', {count:5}) %}
    
    {% if response.success %}
        {% set tweets = response.data %}
        <pre>{{ tweets|json_encode(constant('JSON_PRETTY_PRINT')) }}</pre>
    {% else %}
        <p>An error occured:</p>
        <pre>{{ response|json_encode(constant('JSON_PRETTY_PRINT')) }}</pre>
    {% endif %}
{% endcache %}
```