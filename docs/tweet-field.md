# Tweet Field

## The Field

Tweet fields allow you to paste a tweet URL and use the tweet details, like profile image, tweet text, and others, in your templates.

You can also use the Tweet field with Matrix to combine multiple tweets together.

## Templating

This templates assumes that a **homepage** global has been set up with a **mentions** field, a matrix of Tweet fields :

```twig
{% for mention in homepage.mentions %}

    {% set mentionId = 'mention-' ~ mention.id %}

    <div id="{{ mentionId }}" class="mention {{ mention.type }}">
        {% spaceless %}
            {% if mention.type == 'tweet' %}
                {% set tweet = mention.tweet %}
                {% set user = tweet.user %}

                {% set url = 'https://twitter.com/' ~ user.screen_name ~ '/status/' ~ tweet.id %}
                {% set imageUrl = craft.twitter.getUserImageUrl(user.id, 73) %}
                {% set author = user.name ~ ' (@' ~ user.screen_name ~ ') on Twitter' %}
                {% set quote = tweet.text %}
            {% else %}
                ...
            {% endif %}
        {% endspaceless -%}

        <img src="{{imageUrl}}" />

        <p><cite><a href="{{ url }}" title="{{ author }}">{{ author }}</a></cite></p>

        <blockquote>{{ quote }}</blockquote>

    </div>
{% endfor %}
```
