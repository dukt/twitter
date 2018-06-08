# Requesting the Twitter API over Ajax

Requesting data using Ajax is a great way to improve your page speed, especially if the data depends on a remote API call.

Here is an example of a page which loads its tweets with an Ajax call.

## twitter/index.html

This page performs an ajax call to the `twitter/search.json` template and transforms the data tweets into html tweets.

```twig
<html>
    <head>
        <title>{{ "Twitter Demo"|t }}</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    </head>

    <body>
        <h1>{{ "Twitter Demo"|t }}</h1>
        <div id="tweets">{{ "Loading tweets…"|t }}</div>
    </body>
</html>

{% set js %}

    $.ajax({
        url: "{{ url('twitter/search.json') }}",
        success: function(response)
        {
            console.log('success', response);

            var $ul = $('<ul />');

            $.each(response, function(key, tweet)
            {
                var $li = $('<li>'+tweet.text+'<br><span class="tweetdate">'+tweet.date+'</span></li>').appendTo($ul);
            });

            $('#tweets').html('');
            $ul.appendTo($('#tweets'));
        }
    });

{% endset %}
{% includeJs js %}
```

## twitter/search.json

A request on the API is performed, the response is turned into tweets which are served as JSON:
```twig
{%- spaceless %}
    {% cache for 1 hour %}
        {% set tweets = [] %}
        {% set response = craft.twitter.get('search/tweets', { q:'#craftcms' }) %}

        {% if response.statuses is defined %}
            {% for tweetResponse in response.statuses %}
                {% set tweets = tweets|merge([{
                    id: tweetResponse.id,
                    text: tweetResponse.text | autoLinkTweet,
                    date: tweetResponse.created_at | date("D j M Y")
                }]) %}
            {% endfor %}
        {% endif %}

        {{ tweets|json_encode|raw }}
    {% endcache %}
{% endspaceless -%}
```
