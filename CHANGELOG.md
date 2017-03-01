Changelog
=========

## 2.0.0

### Added
- Craft 3 compatibility.

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
- Fixed API caching

## 1.1.0 - 2016-12-14

### Added
- Added `cacheDuration` config
- Added `twitterFollowButton()` Twig function
- Added `twitterGrid()` Twig function
- Added `twitterMessageButton()` Twig function
- Added `twitterMoment()` Twig function
- Added `twitterTimeline()` Twig function
- Added `twitterTweet()` Twig function
- Added `twitterTweetButton()` Twig function
- Added `twitterVideo()` Twig function
- Added docs URL to the Settings page

### Improved
- Improved plugin description
- Removed `Twitter_TweetFieldType::prepValueFromPost()`
- Replaced `disableCache` config by `enableCache`
- Tweet field now accepts a tweet URL or a tweet ID

### Fixed
- Fixed tweet’s permalink
