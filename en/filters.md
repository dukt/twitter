# Twig Filters

On top of the template filters that Twig comes with, Twitter plugin provides a few of its own.

## autoLinkTweet( options )

Turns mentions, hashtags and urls contained in a text of a tweet into links.

    {{ tweet.text|autoLinkTweet }}

Customize auto-linking with options:

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

Available options:

- `urlClass`
- `usernameClass`
- `listClass`
- `hashtagClass`
- `cashtagClass`
- `noFollow`
- `external`
- `target`