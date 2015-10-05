# Twitter (beta) for Craft CMS

Adds tweet fields to Craft and lets you request Twitter API

-------------------------------------------

## Installation

1. Download the latest release of the plugin
2. Drop the `twitter` plugin folder to `craft/plugins`
3. Install Twitter from the control panel in `Settings > Plugins`


## Templating

### Auto Linking Tweets

Auto link a tweet's text using a twig filter.

    {{ tweet.text|autoLinkTweet }}

    {{ tweet.text|autoLinkTweet({
        urlClass: 'twitter-url',
        usernameClass: 'twitter-username',
        listClass: 'twitter-list',
        hashtagClass: 'twitter-hashtag',
        cashtagClass: 'twitter-cashtag',
        noFollow: false,
        external: false,
        target: '_blank'
    }) }}


### Embedded Tweets

Embedded Tweets make it possible for you take any Tweet and embed it directly in to the content of your article or website. Tweets display with expanded media like photos, videos, and article summaries, and also include real-time retweet and favorite counts.

    {% set tweetId = 133640144317198338 %}

    {% set options = { theme: "dark" } %}

    {{ embedTweet(tweetId, options) }}


### Recent Tweets

Display recent tweets by requesting twitter API from your templates.

    {% set tweets = craft.twitter.get("statuses/user_timeline", {count:5}) %}

    {% if tweets %}

        {% for tweet in tweets %}
            <div class="tweet">

                {{ tweet.text|autoLinkTweet }}

                {% if not loop.last %}
                    <hr>
                {% endif %}


            </div>
        {% endfor %}

    {% else %}
        <p>No tweets</p>
    {% endif %}

## API

### TwitterVariable

- setToken($token)
- getToken()
- get($uri, $params, $headers, $enableCache, $cacheExpire)
- getTweetById($tweetId, $params)
- getUserById($userId, $params)
- getUserImageUrl($userId, $size)