<?php
/**
 * @link      https://dukt.net/craft/twitter/
 * @copyright Copyright (c) 2016, Dukt
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
     * @return array|null
     */
    public function get($uri, $params = array(), $headers = array(), $enableCache = false, $cacheExpire = 0)
    {
        if(!craft()->twitter_plugin->checkDependencies())
        {
            throw new Exception("Twitter plugin dependencies are not met");
        }

        // get from cache

        if(craft()->config->get('disableCache', 'twitter') == true)
        {
            $enableCache = false;
        }

        if($enableCache)
        {
            $token = craft()->twitter_oauth->getToken();

            $key = 'twitter.'.md5($uri.serialize(array($token, $params)));

            $response = craft()->fileCache->get($key);

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
                craft()->fileCache->set($key, $response, $cacheExpire);
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