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


        // Get response from cache

        if(is_null($enableCache))
        {
            $enableCache = craft()->config->get('enableCache', 'twitter');
        }
        else
        {
            if(craft()->config->get('enableCache', 'twitter') === false)
            {
                $enableCache = false;
            }
        }

        if($enableCache)
        {
            $token = craft()->twitter_oauth->getToken();

            $key = 'twitter.'.md5($uri.serialize(array($token, $headers, $options)));

            $response = craft()->fileCache->get($key);

            if($response)
            {
                return $response;
            }
        }


        // Request the API

        try
        {
            $client = $this->getClient();

            $options['query'] = $query;

            $url = $uri.'.json';

            $response = $client->get($url, $headers, $options)->send();

            $jsonResponse = $response->json();

            if($enableCache)
            {
                craft()->fileCache->set($key, $jsonResponse, $cacheExpire);
            }

            return $jsonResponse;
        }
        catch(\Guzzle\Http\Exception\ClientErrorResponseException $e)
        {
            TwitterPlugin::log('Twitter API Error: '.__METHOD__." Couldn't get twitter response", LogLevel::Info, true);
        }
        catch(\Guzzle\Http\Exception\CurlException $e)
        {
            TwitterPlugin::log('Twitter API Error: '.__METHOD__." ".$e->getMessage(), LogLevel::Info, true);
        }
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