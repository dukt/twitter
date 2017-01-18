<?php
/**
 * @link      https://dukt.net/craft/twitter/
 * @copyright Copyright (c) 2017, Dukt
 * @license   https://dukt.net/craft/twitter/docs/license
 */

namespace Craft;

class TwitterVariable
{
    // Public Methods
    // =========================================================================

    /**
     * Performs a GET request on the Twitter API and returns the response.
     *
     * @param uri $uri
     * @param array $params
     * @param array $headers
     * @param bool $enableCache
     * @param int $cacheExpire
     *
     * @return string|null
     */
	public function get($uri, $params = array(), $headers = array(), $enableCache = null, $cacheExpire = 0)
	{
        return craft()->twitter_api->get($uri, $params, $headers, $enableCache, $cacheExpire);
	}

    /**
     * Returns a tweet by its ID.
     *
     * @param int $tweetId
     * @param array $params
     *
     * @return array|null
     */
	public function getTweetById($tweetId, $params = array())
	{
		return craft()->twitter_api->getTweetById($tweetId, $params);
	}

    /**
     * Returns a user by their ID.
     *
     * @param int $userId
     * @param array $params
     *
     * @return array|null
     */
	public function getUserById($userId, $params = array())
	{
		return craft()->twitter_api->getUserById($userId, $params);
	}

    /**
     * Returns a user image from a twitter user ID for given size. Default size is 48.
     *
     * @param int $twitterUserId
     * @param int $size
     *
     * @return string|null
     */
	public function getUserProfileImageResourceUrl($twitterUserId, $size = 48)
	{
	    return TwitterHelper::getUserProfileImageResourceUrl($twitterUserId, $size);
	}

    /**
     * Returns a user image from a twitter user ID for given size. Default size is 48.
     *
     * @param int $twitterUserId
     * @param int $size
     *
     * @deprecated Deprecated in 2.0. Use craft.twitter.getUserProfileImageResourceUrl() instead.
     *
     * @return string|null
     */
	public function getUserImageUrl($twitterUserId, $size = 48)
	{
	    return $this->getUserProfileImageResourceUrl($twitterUserId, $size);
	}
}