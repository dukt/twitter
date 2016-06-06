<?php
namespace Craft;

/**
 * @author    Dukt <support@dukt.net>
 * @copyright Copyright (c) 2016, Dukt
 * @license   https://dukt.net/craft/twitter/docs/license
 * @see       https://dukt.net/craft/twitter/
 * @package   twitter.variables
 * @since     0.9
 */
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
     * @return string|null
     */
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

    /**
     * Returns a tweet by its ID.
     *
     * @param int $tweetId
     * @param array $params
     * @return array|null
     */
	public function getTweetById($tweetId, $params = array())
	{
		return craft()->twitter->getTweetById($tweetId, $params);
	}

    /**
     * Returns a user by their ID.
     *
     * @param int $userId
     * @param array $params
     * @return array|null
     */
	public function getUserById($userId, $params = array())
	{
		return craft()->twitter->getUserById($userId, $params);
	}

    /**
     * Returns a user image from a user ID for given size. Default size is 48.
     *
     * @param int $userId
     * @param int $size
     * @return string|null
     */
	public function getUserImageUrl($userId, $size = 48)
	{
		return UrlHelper::getResourceUrl('twitteruserimages/'.$userId.'/'.$size);
	}
}