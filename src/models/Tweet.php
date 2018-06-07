<?php
/**
 * @link      https://dukt.net/craft/twitter/
 * @copyright Copyright (c) 2018, Dukt
 * @license   https://dukt.net/craft/twitter/docs/license
 */

namespace dukt\twitter\models;

use craft\base\Model;
use dukt\twitter\helpers\TwitterHelper;
use dukt\twitter\Plugin as Twitter;

/**
 * Tweet model class.
 *
 * @author Dukt <support@dukt.net>
 * @since  3.0
 */
class Tweet extends Model
{
    // Properties
    // =========================================================================

    /**
     * @var
     */
    public $remoteId;
    /**
     * @var
     */
    public $data;

    // Public Methods
    // =========================================================================

    /**
     * Returns the tweet’s ID.
     *
     * @return mixed
     */
    public function getRemoteId()
    {
        return $this->remoteId;
    }

    /**
     * Returns the tweet's text.
     *
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function getText()
    {
        $tweetData = $this->getTweetData();

        if (!empty($tweetData['full_text'])) {
            return $tweetData['full_text'];
        }
    }

    /**
     * Returns the tweet’s author user ID.
     *
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function getUserId()
    {
        $tweetData = $this->getTweetData();

        if (!empty($tweetData['user']['id'])) {
            return $tweetData['user']['id'];
        }
    }

    /**
     * Returns the tweet's author user name.
     *
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function getUserName()
    {
        $tweetData = $this->getTweetData();

        if (!empty($tweetData['user']['name'])) {
            return $tweetData['user']['name'];
        }
    }

    /**
     * Returns the tweet's author user profile URL.
     *
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function getUserProfileUrl()
    {
        return 'https://twitter.com/'.$this->getUserScreenName();
    }

    /**
     * Returns the tweet's author user profile remote image URL.
     *
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function getUserProfileRemoteImageUrl()
    {
        $tweetData = $this->getTweetData();

        if (!empty($tweetData['user']['profile_image_url'])) {
            return $tweetData['user']['profile_image_url'];
        }
    }

    /**
     * Returns the tweet's author user profile remote image secure URL.
     *
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function getUserProfileRemoteImageSecureUrl()
    {
        $tweetData = $this->getTweetData();

        if (!empty($tweetData['user']['profile_image_url_https'])) {
            return $tweetData['user']['profile_image_url_https'];
        }
    }

    /**
     * Returns the tweet's author user screen name.
     *
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function getUserScreenName()
    {
        $tweetData = $this->getTweetData();

        if (!empty($tweetData['user']['screen_name'])) {
            return $tweetData['user']['screen_name'];
        }
    }

    /**
     * Returns the creation date of the tweet.
     *
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function getCreatedAt()
    {
        $tweetData = $this->getTweetData();

        if (!empty($tweetData['created_at'])) {
            return $tweetData['created_at'];
        }
    }

    /**
     * Returns the tweet's author user profile image resource url.
     *
     * @param null $size
     *
     * @return null|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \craft\errors\ImageException
     * @throws \yii\base\Exception
     */
    public function getUserProfileImageUrl($size = null)
    {
        $twitterUserId = $this->getUserId();

        return TwitterHelper::getUserProfileImageResourceUrl($twitterUserId, $size);
    }

    /**
     * Returns the URL of the tweet.
     *
     * @return string|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function getUrl()
    {
        $tweetId = $this->getRemoteId();

        try {
            $tweetUserScreenName = $this->getUserScreenName();
        } catch(\GuzzleHttp\Exception\ClientException $e) {
            $tweetUserScreenName = 'username';
        }

        if ($tweetId && $tweetUserScreenName) {
            return 'https://twitter.com/'.$tweetUserScreenName.'/status/'.$tweetId;
        }
    }

    // Protected Methods
    // =========================================================================

    /**
     * Define Attributes
     */
    protected function defineAttributes()
    {
        $attributes = [
            'remoteId' => AttributeType::Number,
            'data' => AttributeType::Mixed,
        ];

        return $attributes;
    }

    // Private Methods
    // =========================================================================

    /**
     * Returns the URL of the tweet.
     *
     * @return string|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    private function getTweetData()
    {
        if (!$this->data) {
            $this->data = $this->getRemoteTweetData();
        }

        return $this->data;
    }

    /**
     * Returns the API's data for a tweet
     *
     * @return array|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    private function getRemoteTweetData()
    {
        if (!empty($this->remoteId)) {
            return Twitter::$plugin->getApi()->getTweetById($this->remoteId);
        }
    }
}
