Twitter Changelog
===================

## Unreleased - 2.0.0 - 2017-01-31

### Added
- Twitter plugin now requires OAuth 2.0+
- Added `Twitter_ApiService::getClient()`
- Added `Twitter_ApiService::saveOriginalUserProfileImage()`
- Added `Twitter_TweetModel`
- Added `TwitterHelper::getUserProfileImageResourceUrl()`
- Added `TwitterPlugin::getSchemaVersion()`
- Added `TwitterService::extractTweetId()`

### Improved
- Improved error handling
- Improved namespaces
- Search widget is now dealing with `Twitter_TweetModel` tweets instead of response array from API
- The user profile image is now getting imported for calls to `Twitter_ApiService::getTweetById()` and `Twitter_ApiService::getTweetByUrl()` methods
- Improved `Twitter_TweetFieldType` to use `Twitter_TweetModel`
- Moved `AutoLink.php` to `lib/`
- Moved `TwitterTwigExtension.php` to `etc/templating/twigextensions/` (and fixed namespaces)
- Renamed `TwitterTrait` to `RequirementsTrait`
- Refactored `Twitter_ApiService::get()` parameters
- Improved caching in `Twitter_ApiService::get()`
- Now making sure that the `userId` is a `int` in `Twitter_ApiService::getUserById()`
- Now making sure that the `tweetId` is a `int` in `Twitter_ApiService::getTweetById()`
- Removed `Twitter_ApiService::request()` 
- Moved `TwitterService::getTweetById()` to `Twitter_ApiService::getTweetById()`
- Moved `TwitterService::getTweetByUrl()` to `Twitter_ApiService::getTweetByUrl()` 
- Moved `TwitterService::getUserById()` to `Twitter_ApiService::getUserById()`
- Moved `TwitterService::extractTweetId()` to `TwitterHelper::extractTweetId()`
- Renamed `TwitterVariable::getUserImageUrl()` to `TwitterVariable::getUserProfileImageResourceUrl()`

### Fixed
- Fixed `TwitterVariable::get()` parameters
- Fixed a bug where `Twitter_ApiService::get()` wouldn’t take `enableCache` plugin config into account
- Fixed minor caching issues in `Twitter_CacheService`

## 1.1.2 - 2017-01-23

### Added
- Schema Version 1.0.0
- The plugin can now be installed with composer via `composer require dukt/craft-twitter`

### Improved

- Updated `nojimage/twitter-text-php` dependency

## 1.1.1 - 2017-01-10

### Improved

- Improved search keywords for the Tweet field type

### Fixed

- Fixed API caching and removed deprecated disableCache config

## 1.1.0 - 2016-12-14

### Added

- Added cacheDuration config
- Added twitterFollowButton() Twig function
- Added twitterGrid() Twig function
- Added twitterMessageButton() Twig function
- Added twitterMoment() Twig function
- Added twitterTimeline() Twig function
- Added twitterTweet() Twig function
- Added twitterTweetButton() Twig function
- Added twitterVideo() Twig function
- Added docs URL to the Settings page

### Improved

- Improved plugin description
- Removed Twitter_TweetFieldType::prepValueFromPost()
- Replaced disableCache config by enableCache
- Tweet field now accepts a tweet URL or a tweet ID

### Fixed

- Fixed tweet’s permalink