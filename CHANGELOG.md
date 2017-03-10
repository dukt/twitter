Changelog
=========

## 1.1.3 - 2017-03-10

### Improved

- The `$cacheExpire` parameter is now `null` instead of being set to `0` for the `\Craft\Twitter_ApiService::get()` method.
- The `$cacheExpire` parameter is now `null` instead of being set to `0` for the `\Craft\TwitterVariable::get()` method.

### Fixed

- Fixed an issue where `\Craft\Twitter_ApiService` was relying on `\Craft\FileCache` instead of `\Craft\Twitter_CacheService` for caching.
- Fixed an issue where the cache duration was not properly calculated into seconds.

## 1.1.2 - 2017-01-23

### Added
-  Schema Version 1.0.0
-  The plugin can now be installed with composer via `composer require dukt/craft-twitter`

### Improved
-  Updated `nojimage/twitter-text-php` dependency


## 1.1.1 - 2017-01-10

### Improved
-  Improved search keywords for the Tweet field type

### Fixed
-  Fixed API caching and removed deprecated `disableCache` config


## 1.1.0 - 2016-12-14

### Added
-  Added `cacheDuration` config
-  Added `twitterFollowButton()` Twig function
-  Added `twitterGrid()` Twig function
-  Added `twitterMessageButton()` Twig function
-  Added `twitterMoment()` Twig function
-  Added `twitterTimeline()` Twig function
-  Added `twitterTweet()` Twig function
-  Added `twitterTweetButton()` Twig function
-  Added `twitterVideo()` Twig function
-  Added docs URL to the Settings page

### Improved
-  Improved plugin description
-  Removed `Twitter_TweetFieldType::prepValueFromPost()`
-  Replaced `disableCache` config by `enableCache`
-  Tweet field now accepts a tweet URL or a tweet ID

### Fixed
-  Fixed tweet’s permalink


## 1.0.32 - 2016-06-20

### Added
-  Added a permanent link to tweets

### Improved
-  Now using the `bigger` Twitter profile image instead of the `normal` one
-  Removed unused disableSave.css
-  Renamed `tweet.css` to `twitter.css`
-  The user&#039;s name and the profile image are now clickable in the Search widget
-  Tweets are now clickable in the Search widget


## 1.0.31 - 2016-06-06

### Added
-  Added time ago to tweet preview in Tweet field
-  Added TwitterHelper::timeAgo()
-  Added TwitterPlugin::getDescription()
-  Added TwitterPlugin::getReleaseFeedUrl()
-  Added twitterTimeAgo twig filter

### Improved
-  Formatting tweet dates as time ago in Search widget
-  Removed `colspan` from Search widget settings
-  Removed Twitter_SearchWidget::getColspan()
-  Improved time ago calculation
-  Moved TwitterController::actionSettings() to Twitter_SettingsController::actionIndex()
-  Now using PHP Traits for checking plugin dependencies
-  Now using secure profile image urls by default

### Fixed
-  Fixed the link to Twitter&#039;s OAuth provider configuration


## 1.0.30 - 2016-02-13

### Added
-  Added `TwitterPlugin::getDocumentationUrl()`

### Improved
-  Moved `Twitter_PluginController::actionInstall()` to `Twitter_InstallController:: actionIndex()`
-  Removed `TwitterHelper::log()`
-  Twitter now has its own log files

### Fixed
-  Fixed link to Twitter OAuth settings


## 1.0.29 - 2016-02-13

### Improved
-  Removed dependency auto download and install
-  Removed deprecated CSS resources

### Fixed
-  Fixed an issue with dependency checker


## 1.0.28 - 2016-02-13

### Added
-  Added support for [OAuth 1.0+](https://dukt.net/craft/oauth)
-  Added support for Craft CMS 2.5
-  Added plugin icons
-  Added `TwitterHelper`
-  Added icon for Search widget
-  Added `Twitter_ApiService`
-  Added `Twitter_CacheService`
-  Added `Twitter_OauthService`

### Improved
-  Now using TwitterPlugin::getSettingsUrl() instead of TwitterPlugin::getSettingsHtml()&#039;s redirect method for specifying plugin&#039;s settings URL
-  Improved Twitter Search widget error handling
-  Improved Twitter_TweetFieldType to display light errors
-  Removed `TwitterVariable::setToken()`
-  Removed `TwitterVariable::getToken()`
-  Moved `TwitterService::api()` to `Twitter_ApiService::api()`
-  Moved `TwitterService::checkDependencies()` to `Twitter_PluginService::checkDependencies()`
-  Moved `TwitterService::deleteToken()` to `Twitter_OauthService::deleteToken()`
-  Moved `TwitterService::get()` to `Twitter_ApiService::get()`
-  Moved `TwitterService::getToken()` to `Twitter_OauthService::getToken()`
-  Moved `TwitterService::saveToken()` to `Twitter_OauthService::saveToken()`
-  Removed `TwitterService::setToken()`
-  Removed Twitter_OauthController::scope
-  Removed Twitter_OauthController::params
-  Moved `TwitterController::actionConnect()` to `Twitter_OauthController::actionConnect()`
-  Moved `TwitterController::actionDisconnect()` to `Twitter_OauthController::actionDisconnect()`

### Fixed
-  Fixed Settings URL in templates
-  Fixed a bug with token in Twitter_ApiService::request()


## 0.9.25 - 2015-05-10

### Added
-  Added TwitterPluginController
-  Added Twitter_PluginService

### Improved
-  Plugin now requires OAuth 0.9.70+
-  Logging error when unable to get an account
-  Renamed Twitter Tweets widget to Twitter Search
-  Improved Tweets widget to display a message when no tweets are found
-  Improved example templates
-  Improved plugin settings

### Fixed
-  Twitter Search widget is now showing a notice when search query is missing
-  Fixed a bug where tweet id was sometimes turned into a float


## 0.9.13 - 2015-03-18

### Added
-  Added AnalyticsPlugin::getRequiredPlugins()
-  Added AnalyticsPlugin::getPluginDependencies()
-  Added AnalyticsPlugin::getPluginDependency()
-  Added dependency checker to check dependencies with other plugins
-  Added Tweets widgets to display recent tweets from a Twitter search

### Improved
-  The plugin now requires OAuth 0.9.63 or above
-  Updated Recent Tweets example
-  Improved settings templates
-  Improved the way a token is deleted: deleting the token instead of setting it to null
-  Improved the way Twitter plugin is getting back to the page which initiated the connect action
-  Token providerHandle and pluginHandle are now automatically populated in token response


## 0.9.10 - 2015-01-11

### Added
-  Added autoLinkTweet twig filter
-  Added embedTweet twig function
-  Added Auto-Linking Tweets templating example
-  Added Embedded Tweets templating example
-  Added twitter-text-php library

### Improved
-  Disabled cache by default

### Fixed
-  Fixed bug with cache keys


## 0.9.9 - 2015-01-11

### Added
-  Added support for new OAuth authentication logic


## 0.9.5 - 2015-01-11

### Added
-  Added support for Twitter URL containing &quot;statuses&quot;

### Fixed
-  Fixed a bug where tweet user link was displaying blue instead of grey


## 0.9.4 - 2015-01-11

### Added
-  Added example templates
-  Added craft.twitter.getUserById(userId, params)
-  Added craft()-&gt;twitter-&gt;api($method, $uri, $params, $headers, $postFields)
-  Added cache handling to craft.twitter.get()
-  Added cache handling to craft()-&gt;twitter-&gt;get()

### Improved
-  Improved craft.twitter.get() arguments : craft.twitter.get(uri, params, headers, enableCache, cacheExpire)
-  Improved craft()-&gt;twitter-&gt;get() arguments : craft()-&gt;twitter-&gt;get($uri, $params, $headers, $enableCache, $cacheExpire)
-  Prevent getSettingsHtml from being called if on settings/plugins listing page


## 0.9.3 - 2015-01-11

### Added
-  Public Beta