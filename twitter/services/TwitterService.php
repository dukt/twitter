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
    private $token;

    /**
     * Get OAuth Token
     */
    public function getToken()
    {
        if($this->token)
        {
            return $this->token;
        }
        else
        {
            // get plugin
            $plugin = craft()->plugins->getPlugin('twitter');

            // get settings
            $settings = $plugin->getSettings();

            // get tokenId
            $tokenId = $settings->tokenId;

            // get token
            $token = craft()->oauth->getTokenById($tokenId);

            if($token && $token->token)
            {
                $this->token = $token;
                return $this->token;
            }
        }
    }

    /**
     * Save OAuth Token
     */
    public function saveToken($token)
    {
        // get plugin
        $plugin = craft()->plugins->getPlugin('twitter');

        // get settings
        $settings = $plugin->getSettings();

        // get tokenId
        $tokenId = $settings->tokenId;

        // get token
        $model = craft()->oauth->getTokenById($tokenId);


        // populate token model

        if(!$model)
        {
            $model = new Oauth_TokenModel;
        }

        $model->providerHandle = 'twitter';
        $model->pluginHandle = 'twitter';
        $model->encodedToken = craft()->oauth->encodeToken($token);

        // save token
        craft()->oauth->saveToken($model);

        // set token ID
        $settings->tokenId = $model->id;

        // save plugin settings
        craft()->plugins->savePluginSettings($plugin, $settings);
    }

    /**
     * Set OAuth Token
     */
    public function setToken($token)
    {
        $this->token = $token;
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

        if(craft()->config->get('disableCache', 'twitter') == true)
        {
            $enableCache = false;
        }

        if($enableCache)
        {

            $key = 'twitter.'.md5($uri.serialize($this->token, $params));

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
        $tokenModel = $this->getToken();

        if(!$tokenModel)
        {
            return null;
        }

        $token = $tokenModel->token;

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