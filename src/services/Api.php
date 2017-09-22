<?php
/**
 * @link      https://dukt.net/craft/twitter/
 * @copyright Copyright (c) 2017, Dukt
 * @license   https://dukt.net/craft/twitter/docs/license
 */

namespace dukt\twitter\services;

use Craft;
use craft\helpers\FileHelper;
use dukt\twitter\Plugin as Twitter;
use League\OAuth1\Client\Credentials\TokenCredentials;
use yii\base\Component;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Subscriber\Oauth\Oauth1;

/**
 * Api Service
 *
 * @author Dukt <support@dukt.net>
 * @since  3.0
 */
class Api extends Component
{
    // Public Methods
    // =========================================================================

    /**
     * Performs a get request on the Twitter API.
     *
     * @param string     $uri
     * @param array|null $query
     * @param array|null $headers
     * @param array      $options
     * @param bool|null  $enableCache
     * @param null|int   $cacheExpire
     *
     * @return array|null
     */
    public function get($uri, array $query = null, array $headers = null, array $options = [], $enableCache = null, $cacheExpire = null)
    {
        // Add query to the request’s options

        if ($query) {
            $options['query'] = $query;
        }

        // Enable Cache

        if (is_null($enableCache)) {
            $enableCache = Twitter::$plugin->getSettings()->enableCache;
        }


        // Try to get response from cache

        if ($enableCache) {
            $response = Twitter::$plugin->getCache()->get([$uri, $headers, $options]);

            if ($response) {
                return $response;
            }
        }


        // Otherwise request the API

        $client = $this->getClient();

        $url = $uri.'.json';

        if ($headers) {
            $options['headers'] = $headers;
        }

        $response = $client->request('GET', $url, $options);

        $jsonResponse = json_decode($response->getBody(), true);

        if ($enableCache) {
            Twitter::$plugin->getCache()->set([$uri, $headers, $options], $jsonResponse, $cacheExpire);
        }

        return $jsonResponse;
    }

    /**
     * Returns a tweet by its ID.
     *
     * @param int|string $tweetId
     * @param array      $query
     *
     * @return array|null
     */
    public function getTweetById($tweetId, $query = [])
    {
        $tweetId = (int)$tweetId;

        $query = array_merge($query, ['id' => $tweetId]);

        $tweet = Twitter::$plugin->getApi()->get('statuses/show', $query);


        // generate user profile image

        $this->saveOriginalUserProfileImage($tweet['user']['id'], $tweet['user']['profile_image_url_https']);

        if (is_array($tweet)) {
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

        if ($tweetId) {
            return $this->getTweetById($tweetId);
        }
    }

    /**
     * Returns a user by their ID.
     *
     * @param int|string $userId
     * @param array      $query
     *
     * @return array|null
     */
    public function getUserById($userId, $query = [])
    {
        $userId = (int)$userId;

        $query = array_merge($query, ['user_id' => $userId]);

        return Twitter::$plugin->getApi()->get('users/show', $query);
    }

    /**
     * Saves the original user profile image for a twitter user ID
     *
     * @param $userId
     * @param $remoteImageUrl
     *
     * @return string|null
     */
    public function saveOriginalUserProfileImage($userId, $remoteImageUrl)
    {
        if ($userId && $remoteImageUrl) {
            $originalFolderPath = Craft::$app->path->getRuntimePath().'/twitter/userimages/'.$userId.'/original/';

            if (!is_dir($originalFolderPath)) {
                FileHelper::createDirectory($originalFolderPath);
            }

            $files = FileHelper::findFiles($originalFolderPath);

            if (count($files) > 0) {
                $imagePath = $files[0];

                return $imagePath;
            }
            
            $remoteImageUrl = str_replace('_normal', '', $remoteImageUrl);
            $fileName = pathinfo($remoteImageUrl, PATHINFO_BASENAME);
            $imagePath = $originalFolderPath.$fileName;

            $client = new \GuzzleHttp\Client();
            $response = $client->request('GET', $remoteImageUrl, [
                'save_to' => $imagePath
            ]);

            if (!$response->getStatusCode() != 200) {
                return null;
            }

            return $imagePath;
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
        $options = [
            'base_uri' => 'https://api.twitter.com/1.1/'
        ];

        $token = Twitter::$plugin->getOauth()->getToken();

        if ($token) {
            $stack = $this->getStack($token);

            $options['auth'] = 'oauth';
            $options['handler'] = $stack;
        }

        return new Client($options);
    }

    /**
     * Get stack
     *
     * @return HandlerStack
     */
    private function getStack(TokenCredentials $token)
    {
        $stack = HandlerStack::create();

        $oauthConsumerKey = Twitter::$plugin->getConsumerKey();
        $oauthConsumerSecret = Twitter::$plugin->getConsumerSecret();

        $middleware = new Oauth1([
            'consumer_key' => $oauthConsumerKey,
            'consumer_secret' => $oauthConsumerSecret,
            'token' => $token->getIdentifier(),
            'token_secret' => $token->getSecret(),
            'signature_method' => 'HMAC-SHA1'
        ]);

        $stack->push($middleware);

        return $stack;
    }
}