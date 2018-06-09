<?php
/**
 * @link      https://dukt.net/twitter/
 * @copyright Copyright (c) 2018, Dukt
 * @license   https://github.com/dukt/twitter/blob/master/LICENSE.md
 */

namespace dukt\twitter\web\twig\variables;

use Craft;
use dukt\twitter\Plugin;
use dukt\twitter\helpers\TwitterHelper;
use Exception;

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
     * @param string     $uri
     * @param array|null $query
     * @param array|null $headers
     * @param array      $options
     * @param null|bool  $enableCache
     * @param null|int   $cacheExpire
     *
     * @return array|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get($uri, array $query = null, array $headers = null, $options = [], $enableCache = null, $cacheExpire = null)
    {
        try {
            return Plugin::getInstance()->getApi()->get($uri, $query, $headers, $options, $enableCache, $cacheExpire);
        } catch (Exception $e) {
            Craft::error('Error requesting Twitter’s API: '.$e->getTraceAsString(), __METHOD__);
        }
    }

    /**
     * Returns a tweet by its URL. Add query parameters to the API request with `query`.
     *
     * @param int   $urlOrId
     * @param array $query
     *
     * @return array|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getTweet($urlOrId, $query = [])
    {
        try {
            return Plugin::getInstance()->getApi()->getTweet($urlOrId, $query);
        } catch (Exception $e) {
            Craft::error('Couldn’t get tweet by URL: '.$e->getTraceAsString(), __METHOD__);
        }
    }

    /**
     * Returns a Twitter user by its ID. Add query parameters to the API request with `query`.
     *
     * @param int   $remoteUserId
     * @param array $query
     *
     * @return array|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getUserById($remoteUserId, $query = [])
    {
        try {
            return Plugin::getInstance()->getApi()->getUserById($remoteUserId, $query);
        } catch (Exception $e) {
            Craft::error('Couldn’t get user by ID: '.$e->getTraceAsString(), __METHOD__);
        }
    }

    /**
     * Returns a user image from a twitter user ID for given size. Default size is 48.
     *
     * @param int $remoteUserId
     * @param int $size
     *
     * @return string|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getUserProfileImageResourceUrl($remoteUserId, $size = 48)
    {
        try {
            return TwitterHelper::getUserProfileImageResourceUrl($remoteUserId, $size);
        } catch (Exception $e) {
            Craft::error('Couldn’t get user profile image resource URL: '.$e->getTraceAsString(), __METHOD__);
        }
    }
}