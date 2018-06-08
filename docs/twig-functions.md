# Twig Functions

On top of the template functions that Twig comes with, Twitter plugin provides a few of its own:

## twitterGrid(url, options)

An [embedded Grid](https://dev.twitter.com/web/embedded-timelines) displays multiple Tweets with edge-to-edge images and video displayed in a grid format.

```twig
<script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>

{{ twitterGrid('https://twitter.com/TwitterDev/timelines/539487832448843776') }}
 ```
 
### Arguments
- **url** — The timeline’s URL.
- **options** — An array of options. The supported options are:
    - `lang` — A supported Twitter language code. Loads text components in the specified language. Note: does not affect the text of the cited Tweet.
    - `limit` — Render a timeline statically, displaying only n number of Tweets. The height parameter has no effect when a Tweet limit is set.

## twitterMoment(url, options)

An [embedded Moment](https://dev.twitter.com/web/embedded-moments) displays multiple Tweets with edge-to-edge images and video displayed in a grid format. 

```twig
<script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>

{{ twitterMoment('https://twitter.com/i/moments/650667182356082688') }}
```

### Arguments
- **url** — The moment’s URL.
- **options** — An array of options. The supported options are:
    - `limit` — Display a maximum number of items between 1 and 20 inclusive.
    - `width` — Set the maximum width of the embedded moment. Minimum: 220.
    - `lang` — A supported Twitter language code. Loads template text components in the specified language. Note: does not affect the original text of an author.
    - `dnt` — When set to true, the Moment and its embedded page do not influence Twitter targeting including suggested accounts.

## twitterTimeline(url, options)

[Embedded timelines](https://dev.twitter.com/web/embedded-timelines) are an easy way to embed multiple Tweets on your website in a compact, single-column view. Display the latest Tweets from a single Twitter account, multiple accounts, or tap into the worldwide conversation around a topic grouped in a search result.

```twig
<script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>

{{ twitterTimeline('https://twitter.com/TwitterDev/timelines/539487832448843776') }}
```


### Arguments
- **url** — The timeline’s URL.
- **options** — An array of options. The supported options are:
    - `lang` — A supported Twitter language code. Loads text components in the specified language. Note: does not affect the text of the cited Tweet.
    - `tweetLimit` — Render a timeline statically, displaying only n number of Tweets. The height parameter has no effect when a Tweet limit is set.
    - `width` — Set the maximum width of the embedded Tweet.
    - `height` — Set a fixed height of the embedded widget.
    - `theme` — When set to dark, displays Tweet with light text over a dark background.
    - `linkColor` — Adjust the color of links, including hashtags and @mentions, inside each Tweet.
    - `chrome` — Toggle the display of design elements in the widget. This parameter is a space-separated list of values.
    - `showReplies` — Show Tweets in response to another Tweet or account.
    - `borderColor` — Adjust the color of borders inside the widget.
    - `ariaPolite` — Apply the specified aria-polite behavior to the rendered timeline. New Tweets may be added to the top of a timeline, affecting screen readers.

## twitterTweet(url, options)

An [embedded Tweet](https://dev.twitter.com/web/embedded-tweets) brings brings the best content created on Twitter into your article or website. An embedded Tweet may include unique photos or a video created for display on Twitter or interactive link previews to highlight additional content.
    
```twig
<script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>

{{ twitterTweet('https://twitter.com/Interior/status/463440424141459456') }}
```


### Arguments
- **url** — The tweet’s URL.
- **options** — An array of options. The supported options are:
    - `conversation` — When set to none, only the cited Tweet will be displayed even if it is in reply to another Tweet.
    - `cards` — When set to hidden, links in a Tweet are not expanded to photo, video, or link previews.
    - `linkColor` — Adjust the color of links, including hashtags and @mentions, inside each Tweet.
    - `theme` — When set to dark, displays Tweet with light text over a dark background.
    - `width` — The maximum width of the rendered Tweet in whole pixels. This value should be between 250 and 550 pixels.
    - `align` — Float the Tweet left, right, or center relative to its container. Typically set to allow text or other content to wrap around the Tweet.
    - `lang` — A supported Twitter language code. Loads text components in the specified language. Note: does not affect the text of the cited Tweet.
    - `dnt` — When set to true, the Tweet and its embedded page do not influence Twitter targeting including suggested accounts.

## twitterVideo(url, options)

An [embedded video](https://dev.twitter.com/web/embedded-video) brings the best video content created on Twitter into your article or website in a video-optimized Tweet display.

```twig
<script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>

{{ twitterVideo('https://twitter.com/twitter/status/560070183650213889') }}
```

### Arguments
- **url** — The tweet’s URL.
- **options** — An array of options. The supported options are:
    - `lang` — A supported Twitter language code. Loads text components in the specified language. Note: does not affect the text of the cited Tweet.

## twitterFollowButton(username, options)

The [Follow button](https://dev.twitter.com/web/follow-button) is a small button displayed on your websites to help users easily follow a Twitter account.

```twig
<script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>

{{ twitterFollowButton('TwitterDev') }}
```

### Arguments
- **username** — The user name.
- **options** — An array of options. The supported options are:
    - `showScreenName` — Set to false to hide the username of the specified account.
    - `showCount` — Set to false to hide the number of accounts following the specified account.
    - `size` — Set to large to display a larger button.
    - `lang` — A supported Twitter language code. Loads text components in the specified language. Note: does not affect the text of the cited Tweet.
    - `dnt` — When set to true, the Tweet and its embedded page do not influence Twitter targeting including suggested accounts.

## twitterMessageButton(recipientId, screenName, text, options)

The [message button](https://dev.twitter.com/web/message-button) is a small button displayed on your website to help viewers easily send a private message (Direct Message) to your account on Twitter.

```twig
<script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>

{{ twitterMessageButton('3805104374', 'furni') }}
```

### Arguments
- **recipientId** — The recipient’s URL.
- **screenName** — The screen name URL.
- **text** — The default text URL.
- **options** — An array of options. The supported options are:
    - `size` — Set to large to display a larger button.

## twitterTweetButton(options)

The [Tweet button](https://dev.twitter.com/web/tweet-button) is a small button displayed on your website to help viewers easily share your content on Twitter.

```twig
<script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>

{{ twitterTweetButton() }}
```

### Arguments
- **options** — An array of options. The supported options are:
    - `text` — Pre-populated text highlighted in the Tweet composer.
    - `url` — URL included with the Tweet.
    - `hashtags` — A comma-separated list of hashtags to be appended to default Tweet text.
    - `via` — Attribute the source of a Tweet to a Twitter username.
    - `related` — A comma-separated list of accounts related to the content of the shared URI.
    - `size` — Set to large to display a larger button.
    - `lang` — A supported Twitter language code. Loads text components in the specified language. Note: does not affect the text of the cited Tweet.
    - `dnt` — When set to true, the Tweet and its embedded page do not influence Twitter targeting including suggested accounts.

## embedTweet(id, options)

_Deprecated in 1.1_

Generates the HTML of an embedded tweet from a tweet ID and optional parameters.

```twig
<script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>

{% set tweetId = 133640144317198338 %}
{% set options = {                    
    theme: 'dark',
    width: 300    
} %}

{{ embedTweet(tweetId) }}
{{ embedTweet(tweetId, options) }}
```

The `widget.js` javascript file only has to be called once per page.


### Arguments
- **url** — The tweet’s ID.
- **options** — An array of options. The supported options are:
    - `align` — Alignment and floating of the tweet. Can take `left`, `right`, and `center`
    - `cards` — Disable cards thread with `hidden`.
    - `conversations` — Disable conversation thread with `none`.
    - `linkColor` — Color of links in hex: `#cc0000`
    - `theme` — Theme of the Tweet. Takes `light or `dark` values.
    - `width` — Width of the Tweet. For example: `300`