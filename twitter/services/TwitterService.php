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
	public function get($api, $params = array(), $opts = array())
	{
		$client = new Client('https://api.twitter.com/1.1');
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

		$provider = craft()->oauth->getProvider('twitter');

		$token = craft()->oauth->getSystemToken('twitter', 'twitter.system');

		if(!$provider || !$token){
			return null;
		}

		$oauth = new \Guzzle\Plugin\Oauth\OauthPlugin(array(
		    'consumer_key'    => $provider->clientId,
		    'consumer_secret' => $provider->clientSecret,
		    'token'           => $token->accessToken,
		    'token_secret'    => $token->secret
		));

		$client->addSubscriber($oauth);

		$key = 'twitter.'.md5($api.serialize($params).serialize($opts));

		$response = craft()->fileCache->get($key);

		if(!$response) {

			$url = $api.'.json';

			if ($params)
			{
				$i = 0;
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

        $token = craft()->oauth->getSystemToken('twitter', 'twitter.system');

        if(!$provider || !$token){
            return null;
        }

        $oauth = new \Guzzle\Plugin\Oauth\OauthPlugin(array(
            'consumer_key'    => $provider->clientId,
            'consumer_secret' => $provider->clientSecret,
            'token'           => $token->accessToken,
            'token_secret'    => $token->secret
        ));

        $client->addSubscriber($oauth);


        // request

        $format = 'json';

        $query = '';

        if($params) {
            $query = http_build_query($params);

            if($query) {
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