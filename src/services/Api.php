<?php
/**
 * @link      https://dukt.net/craft/twitter/
 * @copyright Copyright (c) 2018, Dukt
 * @license   https://dukt.net/craft/twitter/docs/license
 */

namespace dukt\twitter\services;

use Craft;
use craft\helpers\FileHelper;
use dukt\twitter\helpers\TwitterHelper;
use dukt\twitter\models\Tweet;
use dukt\twitter\Plugin;
use League\OAuth1\Client\Credentials\TokenCredentials;
use yii\base\Component;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Subscriber\Oauth\Oauth1;
use DateTime;

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
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \yii\base\InvalidConfigException
     */
    public function get($uri, array $query = null, array $headers = null, array $options = [], $enableCache = null, $cacheExpire = null)
    {
        // Add query to the request’s options

        if ($query) {
            $options['query'] = $query;
        }

        // Enable Cache

        if (is_null($enableCache)) {
            $enableCache = Plugin::getInstance()->getSettings()->enableCache;
        }


        // Try to get response from cache

        if ($enableCache) {
            $response = Plugin::getInstance()->getCache()->get([$uri, $headers, $options]);

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
            Plugin::getInstance()->getCache()->set([$uri, $headers, $options], $jsonResponse, $cacheExpire);
        }

        return $jsonResponse;
    }

    /**
     * Returns a tweet by its URL or ID.
     *
     * @param       $urlOrId
     *
     * @param array $query
     *
     * @return Tweet|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function getTweet($urlOrId, $query = [])
    {
        $tweetId = TwitterHelper::extractTweetId($urlOrId);

        if ($tweetId) {
            return $this->getTweetById($tweetId, $query);
        }
    }

    /**
     * Returns a user by their ID.
     *
     * @param int|string $userId
     * @param array      $query
     *
     * @return array|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \yii\base\InvalidConfigException
     */
    public function getUserById($userId, $query = [])
    {
        $userId = (int)$userId;

        $query = array_merge($query, ['user_id' => $userId]);

        return $this->get('users/show', $query);
    }

    /**
     * Populate tweet object from data array.
     *
     * @param Tweet $tweet
     * @param array $data
     *
     * @return Tweet
     */
    public function populateTweetFromData(Tweet $tweet, array $data): Tweet
    {
        if (isset($data['created_at'])) {
            $tweet->createdAt = DateTime::createFromFormat('D M d H:i:s O Y', $data['created_at']);
        }

        $tweet->data = $data;
        $tweet->text = $data['full_text'] ?? null;
        $tweet->remoteId = $data['id'] ?? null;
        $tweet->remoteUserId = $data['user']['id'] ?? null;
        $tweet->username = $data['user']['name'] ?? null;
        $tweet->userProfileRemoteImageSecureUrl = $data['user']['profile_image_url_https'] ?? null;
        $tweet->userProfileRemoteImageUrl = $data['user']['profile_image_url'] ?? null;
        $tweet->userScreenName = $data['user']['screen_name'] ?? null;

        return $tweet;
    }

    /**
     * Saves the original user profile image for a twitter user ID
     *
     * @param $userId
     * @param $remoteImageUrl
     *
     * @return string|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \yii\base\Exception
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

            $client = new Client();
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
     * @throws \yii\base\InvalidConfigException
     */
    private function getClient()
    {
        $options = [
            'base_uri' => 'https://api.twitter.com/1.1/'
        ];

        $token = Plugin::getInstance()->getOauth()->getToken();

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
     * @param TokenCredentials $token
     *
     * @return HandlerStack
     */
    private function getStack(TokenCredentials $token)
    {
        $stack = HandlerStack::create();

        $oauthConsumerKey = Plugin::getInstance()->getConsumerKey();
        $oauthConsumerSecret = Plugin::getInstance()->getConsumerSecret();

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

    /**
     * Returns a tweet by its ID.
     *
     * @param       $tweetId
     * @param array $query
     *
     * @return Tweet|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    private function getTweetById($tweetId, $query = [])
    {
        $tweetId = (int)$tweetId;

        $query = array_merge($query, [
            'id' => $tweetId,
            'tweet_mode' => 'extended'
        ]);

        $data = $this->get('statuses/show', $query);

        if (!$data) {
            return null;
        }

        $tweet = new Tweet();
        $this->populateTweetFromData($tweet, $data);

        // generate user profile image
        $this->saveOriginalUserProfileImage($tweet->remoteUserId, $tweet->userProfileRemoteImageSecureUrl);

        return $tweet;
    }
}