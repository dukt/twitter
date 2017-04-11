# Embedded Tweets

[Embedded Tweets](https://dev.twitter.com/web/embedded-tweets) make it possible for you take any Tweet and embed it directly in to the content of your article or website.
Tweets display with expanded media like photos, videos, and article summaries, and also include real-time retweet and favorite counts.

{asset:1257:img}

## Usage

Embed a tweet by using the [`embedTweet`]({entry:1254:url}#embedTweet) function:

    <script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>

    {% set tweetId = 133640144317198338 %}

    {{ embedTweet(tweetId) }}

## Customize

You can customize embedded tweets with several options:

    <script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>

    {% set tweetId = 133640144317198338 %}
    {% set options = {
        align: 'left',
        cards: 'hidden',
        conversations: 'none',
        linkColor: '#cc0000',                    
        theme: 'dark',
        width: 300    
    } %}

    {{ embedTweet(tweetId, options) }}

See [embedTweet]({entry:1254:url}#embedTweet) twig function for more.