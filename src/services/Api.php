<?php
/**
 * @link      https://dukt.net/twitter/
 * @copyright Copyright (c) Dukt
 * @license   https://github.com/dukt/twitter/blob/master/LICENSE.md
 */

namespace dukt\twitter\services;

use Craft;
use craft\helpers\FileHelper;
use DateTime;
use dukt\twitter\helpers\TwitterHelper;
use dukt\twitter\models\Tweet;
use dukt\twitter\Plugin;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Subscriber\Oauth\Oauth1;
use League\OAuth1\Client\Credentials\TokenCredentials;
use yii\base\Component;

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
     * @param array|null $query
     * @param array|null $headers
     * @param bool|null  $enableCache
     * @param null|int   $cacheExpire
     * @return array|null
     * @throws GuzzleException
     */
    public function get(string $uri, array $query = null, array $headers = null, array $options = [], $enableCache = null, $cacheExpire = null)
    {
        // Add query to the request’s options

        if ($query) {
            $options['query'] = $query;
        }

        // Enable Cache

        if (null === $enableCache) {
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

        $url = $uri . '.json';

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
     * @param string|int $urlOrId
     * @param array|null $query
     *
     * @return Tweet|null
     * @throws GuzzleException
     * @throws \yii\base\Exception
     */
    public function getTweet($urlOrId, array $query = null)
    {
        $tweetId = TwitterHelper::extractTweetId($urlOrId);

        if ($tweetId) {
            return $this->getTweetById($tweetId, $query);
        }

        return null;
    }

    /**
     * Returns a user by their ID.
     *
     * @param int|string $userId
     *
     * @return array|null
     * @throws GuzzleException
     * @param mixed[] $query
     */
    public function getUserById($userId, array $query = [])
    {
        $userId = (int)$userId;

        $query = array_merge($query, ['user_id' => $userId]);

        return $this->get('users/show', $query);
    }

    /**
     * Parses tweet data and returns a tweet model.
     *
     * @param array $data
     * @return Tweet
     */
    public function parseTweetData(array $data): Tweet
    {
        $tweet = new Tweet();

        $this->populateTweetFromData($tweet, $data);

        return $tweet;
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
     * @param int $userId
     * @param string $remoteImageUrl
     *
     * @return string|null
     * @throws GuzzleException
     * @throws \yii\base\Exception
     */
    public function saveOriginalUserProfileImage($userId, $remoteImageUrl)
    {
        if (!$userId || !$remoteImageUrl) {
            return null;
        }

        $originalFolderPath = Craft::$app->path->getRuntimePath() . '/twitter/userimages/' . $userId . '/original/';

        if (!is_dir($originalFolderPath)) {
            FileHelper::createDirectory($originalFolderPath);
        }

        $files = FileHelper::findFiles($originalFolderPath);

        if ($files !== []) {
            $imagePath = $files[0];

            return $imagePath;
        }

        $remoteImageUrl = str_replace('_normal', '', $remoteImageUrl);
        $fileName = pathinfo($remoteImageUrl, PATHINFO_BASENAME);
        $imagePath = $originalFolderPath . $fileName;

        $client = Craft::createGuzzleClient();
        $response = $client->request('GET', $remoteImageUrl, [
            'sink' => $imagePath,
        ]);

        if (!$response->getStatusCode() != 200) {
            return null;
        }

        return $imagePath;
    }

    // Private Methods
    // =========================================================================

    /**
     * Returns the authenticated client.
     *
     * @return Client
     */
    private function getClient()
    {
        $options = [
            'base_uri' => 'https://api.twitter.com/1.1/',
        ];

        $token = Plugin::getInstance()->getOauth()->getToken();

        if ($token !== null) {
            $stack = $this->getStack($token);

            $options['auth'] = 'oauth';
            $options['handler'] = $stack;
        }

        return Craft::createGuzzleClient($options);
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
            'signature_method' => 'HMAC-SHA1',
        ]);

        $stack->push($middleware);

        return $stack;
    }

    /**
     * Returns a tweet by its ID.
     *
     * @param int $tweetId
     * @param array|null $query
     *
     * @return Tweet|null
     * @throws GuzzleException
     * @throws \yii\base\Exception
     */
    private function getTweetById($tweetId, array $query = null)
    {
        $tweetId = (int)$tweetId;

        if (!$query) {
            $query = [];
        }

        $query = array_merge($query, [
            'id' => $tweetId,
            'tweet_mode' => 'extended',
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
