<?php

/**
 * Twitter plugin for Craft CMS
 *
 * @package   Twitter
 * @author    Benjamin David
 * @copyright Copyright (c) 2014, Dukt
 * @link      https://dukt.net/craft/twitter/
 * @license   https://dukt.net/craft/twitter/docs/license
 */

namespace Craft;

use Guzzle\Http\Client;

class TwitterService extends BaseApplicationComponent
{
    /**
     * Save OAuth Token
     */
    public function saveToken($token)
    {
        // get plugin
        $plugin = craft()->plugins->getPlugin('twitter');

        // get settings
        $settings = $plugin->getSettings();

        // encode token
        $settings['token'] = craft()->oauth->encodeToken($token);

        // save token to plugin settings
        craft()->plugins->savePluginSettings($plugin, $settings);
    }

    /**
     * Get OAuth Token
     */
    public function getToken()
    {
        // get plugin
        $plugin = craft()->plugins->getPlugin('twitter');

        // get settings
        $settings = $plugin->getSettings();

        if(!empty($settings['token']))
        {
            // get token from settings
            $token = craft()->oauth->decodeToken($settings['token']);

            // refresh token if needed
            if(craft()->oauth->refreshToken('twitter', $token))
            {
                // save token
                $this->saveToken($token);
            }

            // return token
            return $token;
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
    public function get($uri, $params = array(), $headers = array(), $enableCache = true, $cacheExpire = 0)
    {
        // get from cache

        if($enableCache)
        {

            $key = 'twitter.'.md5($uri.serialize($params));

            $response = craft()->fileCache->get($key);

            if($response)
            {
                return $response;
            }
        }


        // run request

        try
        {
            $response = $this->api('get', $uri, $params, $headers);


            // cache response

            if($enableCache)
            {
                craft()->fileCache->set($key, $response, $cacheExpire);
            }

            return $response;
        }
        catch(\Guzzle\Http\Exception\ClientErrorResponseException $e)
        {
            Craft::log(__METHOD__." Couldn't get twitter response", LogLevel::Info, true);
        }
        catch(\Guzzle\Http\Exception\CurlException $e)
        {
            Craft::log(__METHOD__." ".$e->getMessage(), LogLevel::Info, true);
        }
    }

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

    public function api($method = 'get', $uri, $params = null, $headers = null, $postFields = null)
    {
        // client

        $client = new Client('https://api.twitter.com/1.1');

        $provider = craft()->oauth->getProvider('twitter');

        $token = craft()->twitter->getToken();

        if(!$provider || !$token)
        {
            return null;
        }

        $oauth = new \Guzzle\Plugin\Oauth\OauthPlugin(array(
            'consumer_key'    => $provider->clientId,
            'consumer_secret' => $provider->clientSecret,
            'token'           => $token->getAccessToken(),
            'token_secret'    => $token->getAccessTokenSecret()
        ));

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

    /**
     * Returns a tweet by its ID.
     *
     * @param int $tweetId
     * @param array $params
     * @return array|null
     */
    public function getTweetById($tweetId, $params = array())
    {
        $params = array_merge($params, array('id' => $tweetId));
        return $this->get('statuses/show', $params);
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
        $params = array_merge($params, array('user_id' => $userId));
        return $this->get('users/show', $params);
    }
}