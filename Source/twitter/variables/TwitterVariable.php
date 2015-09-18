<?php
/**
 * @link      https://dukt.net/craft/twitter/
 * @copyright Copyright (c) 2015, Dukt
 * @license   https://dukt.net/craft/twitter/docs/license
 */

namespace Craft;

class TwitterVariable
{
    // Public Methods
    // =========================================================================

	public function get($uri, $params = array(), $headers = array(), $enableCache = false, $cacheExpire = 0)
	{
        try
        {
		     return craft()->twitter_api->get($uri, $params, $headers, $enableCache, $cacheExpire);
        }
        catch(\Exception $e)
        {
            return false;
        }
	}

	public function getTweetById($tweetId, $params = array())
	{
		return craft()->twitter->getTweetById($tweetId, $params);
	}

	public function getUserById($userId, $params = array())
	{
		return craft()->twitter->getUserById($userId, $params);
	}

	public function getUserImageUrl($userId, $size = 48)
	{
		return UrlHelper::getResourceUrl('twitteruserimages/'.$userId.'/'.$size);
	}
}