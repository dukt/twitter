<?php

namespace Craft;

use Guzzle\Http\Client;

class TwitterService extends BaseApplicationComponent
{
	public function get($api, $params = array(), $opts = array())
	{
		$client = new Client('https://api.twitter.com/1.1');

		$provider = craft()->oauth->getProvider('twitter');

		$token = craft()->oauth->getSystemToken('twitter', 'twitter.system');

		if(!$token){
			return null;
		}

		if(!$token->token) {
			return null;
		}

		$realToken = $token->getRealToken();

		$oauth = new \Guzzle\Plugin\Oauth\OauthPlugin(array(
		    'consumer_key'    => $provider->clientId,
		    'consumer_secret' => $provider->clientSecret,
		    'token'           => $realToken->access_token,
		    'token_secret'    => $realToken->secret
		));

		$client->addSubscriber($oauth);

		$key = 'twitter.'.md5($api.serialize($params).serialize($opts));

		$response = craft()->fileCache->get($key);

		if(!$response) {

			$url = $api.'.json';

			if ($params)
			{
				$i = 0;

				foreach ($params as $paramKey => $paramValue)
				{
					$url .= ($i == 0 ? '?' : '&') . $paramKey.'='.$paramValue;
					$i++;
				}
			}

			$response = $client->get($url, $opts)->send();

			$response = $response->json();

			craft()->fileCache->set($key, $response);
		}

		return $response;
	}
}