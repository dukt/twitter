# Requesting the Twitter API

The Twitter plugin allows you to perform requests on the Twitter API from inside templates.

## Performing GET Requests
Use the [`craft.twitter.get()`](craft-twitter.md#get-uri-query-headers-options-enablecache-cacheexpire) method to perform GET requests to the Twitter API.
You can use any GET request listed in the [Twitter API reference](https://developer.twitter.com/en/docs/api-reference-index.html).

This example displays recent tweets by calling `statuses/user_timeline` API method:

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

See more examples in the [dukt/twitter-demo](https://github.com/dukt/twitter-demo) repository.

## Caching API Responses
When requesting the API, it is recommended to cache responses in order to reduce api calls and server load.

```twig
{% cache for 1 day %}
    {% set response = craft.twitter.get('statuses/user_timeline', {count:5}) %}
    
    {% if response.success %}
        {% set tweets = response.data %}
        <pre>{{ tweets|json_encode(constant('JSON_PRETTY_PRINT')) }}</pre>
    {% else %}
        <p>An error occured:</p>
        <pre>{{ response.data|json_encode(constant('JSON_PRETTY_PRINT')) }}</pre>
    {% endif %}
{% endcache %}
```