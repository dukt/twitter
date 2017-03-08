<?php
/**
 * @link      https://dukt.net/craft/twitter/
 * @copyright Copyright (c) 2017, Dukt
 * @license   https://dukt.net/craft/twitter/docs/license
 */

namespace dukt\twitter\web\twig\variables;

use Craft;
use dukt\twitter\Plugin as Twitter;
use dukt\twitter\helpers\TwitterHelper;

/**
 * Twitter Variable
 *
 * @author Dukt <support@dukt.net>
 * @since  3.0
 */
class TwitterVariable
{
    // Public Methods
    // =========================================================================

    /**
     * Performs a GET request on the Twitter API and returns the response.
     *
     * @param string $uri
     * @param array|null $query
     * @param array|null $headers
     * @param array $options
     * @param bool $enableCache
     * @param int $cacheExpire
     *
     * @return string|null
     */
    public function get($uri, array $query = null, array $headers = null, $options = array(), $enableCache = null, $cacheExpire = 0)
    {
        try
        {
            return Twitter::$plugin->getApi()->get($uri, $query, $headers, $options, $enableCache, $cacheExpire);
        }
        catch(\Exception $e)
        {
            Craft::info("Error requesting Twitter’s API: ".$e->getMessage(), __METHOD__);
        }
    }

    /**
     * Returns a tweet by its ID. Add query parameters to the API request with `query`.
     *
     * @param int $tweetId
     * @param array $params
     *
     * @return array|null
     */
    public function getTweetById($tweetId, $query = array())
    {
        try
        {
            return Twitter::$plugin->getApi()->getTweetById($tweetId, $query);
        }
        catch(\Exception $e)
        {
            Craft::info("Couldn’t get tweet by ID: ".$e->getMessage(), __METHOD__);
        }
    }

    /**
     * Returns a Twitter user by its ID. Add query parameters to the API request with `query`.
     *
     * @param int $twitterUserId
     * @param array $query
     *
     * @return array|null
     */
    public function getUserById($twitterUserId, $query = array())
    {
        try
        {
            return Twitter::$plugin->getApi()->getUserById($twitterUserId, $query);
        }
        catch(\Exception $e)
        {
            Craft::info("Couldn’t get user by ID: ".$e->getMessage(), __METHOD__);
        }
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
        try
        {
            return TwitterHelper::getUserProfileImageResourceUrl($twitterUserId, $size);
        }
        catch(\Exception $e)
        {
            Craft::info("Couldn’t get user profile image resource URL: ".$e->getMessage(), __METHOD__);
        }
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