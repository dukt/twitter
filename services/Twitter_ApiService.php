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
     * Performs a request on the Twitter API
     *
     * @param string $method
     * @param string $uri
     * @param array $params
     * @param array $headers
     * @param array $postFields
     * @return array|null
     */
    public function request($method = 'get', $uri, $params = null, $headers = null, $postFields = null)
    {
        // client
        $client = new Client('https://api.twitter.com/1.1');

        $provider = craft()->oauth->getProvider('twitter');

        $token = craft()->twitter_oauth->getToken();

        if($token)
        {
            $oauth = $provider->getSubscriber($token);

            $client->addSubscriber($oauth);


            // request

            $format = 'json';

            $query = '';

            if($params)
            {
                $query = http_build_query($params);

                if($query)
                {
                    $query = '?'.$query;
                }
            }

            $url = $uri.'.'.$format.$query;

            $response = $client->get($url, $headers, $postFields)->send();

            $response = $response->json();

            return $response;
        }
    }

    /**
     * Performs a get request on the Twitter API
     *
     * @param string $uri
     * @param array $params
     * @param array $headers
     * @param array $postFields
     * @param bool $enableCache
     * @param null|int $cacheExpire
     * @return array|null
     */
    public function get($uri, $params = array(), $headers = array(), $enableCache = null, $cacheExpire = null)
    {
        if(!craft()->twitter->checkDependencies())
        {
            throw new Exception("Twitter plugin dependencies are not met");
        }

        // get from cache

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

            $response = craft()->twitter_cache->get(['twitter.api.get', $token, $params]);

            if($response)
            {
                return $response;
            }
        }


        // run request

        try
        {
            $response = $this->request('get', $uri, $params, $headers);
            // cache response

            if($enableCache)
            {
                craft()->twitter_cache->set(['twitter.api.get', $token, $params], $response, $cacheExpire);
            }

            return $response;
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
}