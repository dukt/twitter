# Updating Twitter

When an update is available, users with the permission to perform updates will see a badge in the CP next to the Utilities navigation item in the sidebar. Click on Utilities and then choose Updates. You can also use the Updates widget on the Control Panel dashboard, which is installed by default.

This section displays both Craft CMS updates and plugin updates. Click the Update button for Twitter to initiate the self-updating process.

You can run all of the updates (Craft, all plugin updates available) using the Update All button at the top left of the Updates page.

## Changes in Twitter 2.1

Twitter 2.1 brings some breaking changes that will require you to update your templates:
- **[Tweet Model](tweet-model.md)** – The properties and methods have changed for the Tweet model.
- **[Tweet Field](tweet-field.md)** – The tweet field now returns an URL as a string instead of a Tweet object. The tweet needs to be retrieved manually using the `craft.twitter.getTweet()` method.
- **[craft.twitter](craft-twitter.md)** – The methods for retrieving a tweet have changed, `craft.twitter.getTweetByUrl()` and `craft.twitter.getTweetById()` methods have been merged into a single `the craft.twitter.getTweet()` method.