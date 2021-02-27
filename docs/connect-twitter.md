# Connecting to Twitter

## OAuth configuration

[Create a Twitter application](https://dev.twitter.com/apps) in order to get an OAuth **Consumer Key** and a **Consumer Secret**.

Then go to **Craft CP → Settings → Twitter  → Configure OAuth** in order to fill your consumer key and secret, and save.

### Redirect URI
Twitter [doesn’t allow query parameters in callback URLs](https://developer.twitter.com/en/docs/basics/apps/guides/callback-urls) anymore, so if your redirect URI does contain query parameters, change Craft’s [usePathInfo](https://docs.craftcms.com/v3/config/config-settings.html#usepathinfo) config to `true` to use PATH_INFO.

## Connect

OAuth is now configured for Twitter, you can go back to Twitter’s settings in **Craft CP → Settings → Twitter** and click “Connect”.
