# Auto Linking Tweets

Auto-link mentions, hashtags, and urls of a tweet by using the [`autoLinkTweet`](filters.md#autoLinkTweet) filter:


    {{ tweet.text|autoLinkTweet) }}

    {{ tweet.text|autoLinkTweet({
        noFollow: false,
        target: "_blank"
    }) }}

    {{ tweet.text|autoLinkTweet({
        urlClass: "twitter-url",
        usernameClass: "twitter-username",
        listClass: "twitter-list",
        hashtagClass: "twitter-hashtag",
        cashtagClass: "twitter-cashtag",
        noFollow: false,
        external: false,
        target: "_blank"
    }) }}

See [autoLinkTweet](filters.md#autoLinkTweet) twig function for more.