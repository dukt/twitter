<?php
/**
 * @link      https://dukt.net/craft/twitter/
 * @copyright Copyright (c) 2017, Dukt
 * @license   https://dukt.net/craft/twitter/docs/license
 */

namespace Craft;

use Guzzle\Http\Client;

class Twitter_ApiService extends BaseApplicationComponent
{
    // Public Methods
    // =========================================================================

    /**
     * Performs a get request on the Twitter API.
     *
     * @param string $uri
     * @param array|null $query
     * @param array|null $headers
     * @param array $options
     * @param bool|null $enableCache
     * @param int $cacheExpire
     *
     * @return array|null
     */
    public function get($uri, array $query = null, array $headers = null, array $options = array(), $enableCache = null, $cacheExpire = 0)
    {
        if(!craft()->twitter->checkDependencies())
        {
            throw new Exception("Twitter plugin dependencies are not met");
        }


        // Add query to the request’s options

        if($query)
        {
            $options['query'] = $query;
        }

        // Enable Cache

        if(is_null($enableCache))
        {
            $enableCache = craft()->config->get('enableCache', 'twitter');
        }


        // Try to get response from cache

        if($enableCache)
        {
            $response = craft()->twitter_cache->get([$uri, $headers, $options]);

            if($response)
            {
                return $response;
            }
        }


        // Otherwise request the API

        try
        {
            $client = $this->getClient();

            $url = $uri.'.json';

            $response = $client->get($url, $headers, $options)->send();

            $jsonResponse = $response->json();

            if($enableCache)
            {
                craft()->twitter_cache->set([$uri, $headers, $options], $jsonResponse, $cacheExpire);
            }

            return $jsonResponse;
        }
        catch(\Guzzle\Http\Exception\ClientErrorResponseException $e)
        {
            TwitterPlugin::log('Twitter API Error: '.__METHOD__." Couldn't get twitter response", LogLevel::Error);
        }
        catch(\Guzzle\Http\Exception\CurlException $e)
        {
            TwitterPlugin::log('Twitter API Error: '.__METHOD__." ".$e->getMessage(), LogLevel::Error);
        }
    }

    /**
     * Returns a tweet by its ID.
     *
     * @param int|string $tweetId
     * @param array $query
     *
     * @return array|null
     */
    public function getTweetById($tweetId, $query = [])
    {
        $tweetId = (int) $tweetId;

        $query = array_merge($query, array('id' => $tweetId));

        $tweet = craft()->twitter_api->get('statuses/show', $query);

        if(is_array($tweet))
        {
            return $tweet;
        }
    }

    /**
     * Returns a tweet by its URL or ID.
     *
     * @param string $urlOrId
     *
     * @return array|null
     */
    public function getTweetByUrl($urlOrId)
    {
        $tweetId = TwitterHelper::extractTweetId($urlOrId);

        if($tweetId)
        {
            return $this->getTweetById($tweetId);
        }
    }

    /**
     * Returns a user by their ID.
     *
     * @param int|string $userId
     * @param array $query
     *
     * @return array|null
     */
    public function getUserById($userId, $query = [])
    {
        $userId = (int) $userId;

        $query = array_merge($query, array('user_id' => $userId));

        return craft()->twitter_api->get('users/show', $query);
    }

    // Private Methods
    // =========================================================================

    /**
     * Get the authenticated client
     *
     * @return Client
     */
    private function getClient()
    {
        $client = new Client('https://api.twitter.com/1.1');

        $provider = craft()->oauth->getProvider('twitter');

        $token = craft()->twitter_oauth->getToken();

        if($token)
        {
            $oauth = $provider->getSubscriber($token);

            $client->addSubscriber($oauth);

            return $client;
        }
    }
}