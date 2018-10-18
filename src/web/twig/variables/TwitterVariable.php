<?php
/**
 * @link      https://dukt.net/twitter/
 * @copyright Copyright (c) 2018, Dukt
 * @license   https://github.com/dukt/twitter/blob/master/LICENSE.md
 */

namespace dukt\twitter\web\twig\variables;

use Craft;
use craft\helpers\Json;
use dukt\twitter\Plugin;
use dukt\twitter\helpers\TwitterHelper;
use dukt\twitter\models\Tweet;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
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
     * Perform a GET request on the Twitter API.
     *
     * @param string     $uri
     * @param array|null $query
     * @param array|null $headers
     * @param array      $options
     * @param null|bool  $enableCache
     * @param null|int   $cacheExpire
     *
     * @return array
     * @throws GuzzleException
     */
    public function get($uri, array $query = null, array $headers = null, $options = [], $enableCache = null, $cacheExpire = null): array
    {
        try {
            return [
                'success' => true,
                'data' => Plugin::getInstance()->getApi()->get($uri, $query, $headers, $options, $enableCache, $cacheExpire)
            ];
        } catch (ClientException $e) {
            Craft::error('Error requesting Twitter’s API: '.$e->getTraceAsString(), __METHOD__);
            return [
                'success' => false,
                'data' => Json::decodeIfJson($e->getResponse()->getBody()->getContents()),
            ];
        }
    }

    /**
     * Returns a tweet by its URL. Add query parameters to the API request with `query`.
     *
     * @param string|int $urlOrId
     * @param array|null $query
     *
     * @return Tweet|null
     * @throws \yii\base\Exception
     */
    public function getTweet($urlOrId, array $query = null)
    {
        try {
            return Plugin::getInstance()->getApi()->getTweet($urlOrId, $query);
        } catch (GuzzleException $e) {
            Craft::error('Couldn’t get tweet by URL: '.$e->getTraceAsString(), __METHOD__);
            return null;
        }
    }

    /**
     * Returns a Twitter user by its ID. Add query parameters to the API request with `query`.
     *
     * @param int   $remoteUserId
     * @param array $query
     *
     * @return array|null
     */
    public function getUserById($remoteUserId, $query = [])
    {
        try {
            return Plugin::getInstance()->getApi()->getUserById($remoteUserId, $query);
        } catch (GuzzleException $e) {
            Craft::error('Couldn’t get user by ID: '.$e->getTraceAsString(), __METHOD__);
            return null;
        }
    }

    /**
     * Returns a user image from a twitter user ID for given size. Default size is 48.
     *
     * @param int $remoteUserId
     * @param int $size
     *
     * @return string|null
     * @throws \craft\errors\ImageException
     * @throws \yii\base\Exception
     */
    public function getUserProfileImageResourceUrl($remoteUserId, $size = 48)
    {
        try {
            return TwitterHelper::getUserProfileImageResourceUrl($remoteUserId, $size);
        } catch (GuzzleException $e) {
            Craft::error('Couldn’t get user profile image resource URL: '.$e->getTraceAsString(), __METHOD__);
            return null;
        }
    }

    /**
     * Parses tweet data and returns a Tweet model.
     *
     * @param array $data Tweet’s API data as an array.
     * @return Tweet
     */
    public function parseTweetData(array $data): Tweet
    {
        return Plugin::getInstance()->getApi()->parseTweetData($data);
    }
}