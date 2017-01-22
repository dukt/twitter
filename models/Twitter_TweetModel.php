<?php
/**
 * @link      https://dukt.net/craft/twitter/
 * @copyright Copyright (c) 2017, Dukt
 * @license   https://dukt.net/craft/twitter/docs/license
 */

namespace Craft;

/**
 * Twitter Tweet Model
 */
class Twitter_TweetModel extends BaseModel
{
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
     */
    public function getText()
    {
        $tweetData = $this->getTweetData();

        if(!empty($tweetData['text']))
        {
            return $tweetData['text'];
        }
    }

    /**
     * Returns the tweet’s user ID.
     *
     * @return mixed
     */
    public function getUserId()
    {
        $tweetData = $this->getTweetData();

        if(!empty($tweetData['user']['id']))
        {
            return $tweetData['user']['id'];
        }
    }

    /**
     * Returns the tweet's user name.
     *
     * @return mixed
     */
    public function getUserName()
    {
        $tweetData = $this->getTweetData();

        if(!empty($tweetData['user']['name']))
        {
            return $tweetData['user']['name'];
        }
    }

    /**
     * Returns the user profile URL.
     *
     * @return mixed
     */
    public function getUserProfileUrl()
    {
        return 'https://twitter.com/' . $this->getUserScreenName();
    }

    /**
     * Returns the user profile remote image URL.
     *
     * @return mixed
     */
    public function getUserProfileRemoteImageUrl()
    {
        $tweetData = $this->getTweetData();

        if(!empty($tweetData['user']['profile_image_url']))
        {
            return $tweetData['user']['profile_image_url'];
        }
    }

    /**
     * Returns the user profile remote image secure URL.
     *
     * @return mixed
     */
    public function getUserProfileRemoteImageSecureUrl()
    {
        $tweetData = $this->getTweetData();

        if(!empty($tweetData['user']['profile_image_url_https']))
        {
            return $tweetData['user']['profile_image_url_https'];
        }
    }

    /**
     * Returns the user screen name.
     *
     * @return mixed
     */
    public function getUserScreenName()
    {
        $tweetData = $this->getTweetData();

        if(!empty($tweetData['user']['screen_name']))
        {
            return $tweetData['user']['screen_name'];
        }
    }

    /**
     * Returns the creation date of the tweet.
     *
     * @return mixed
     */
    public function getCreatedAt()
    {
        $tweetData = $this->getTweetData();

        if(!empty($tweetData['created_at']))
        {
            return $tweetData['created_at'];
        }
    }

    /**
     * Returns the author of the tweet.
     *
     * @return mixed
     */
    public function getUser()
    {
        $tweetData = $this->getTweetData();

        if(!empty($tweetData['user']))
        {
            return $tweetData['user'];
        }
    }

    /**
     * @param null $size
     *
     * @return null|string
     */
    public function getUserProfileImageUrl($size = null)
    {
        $twitterUserId = $this->getUserId();

        return TwitterHelper::getUserProfileImageResourceUrl($twitterUserId, $size);
    }

    /**
     * Returns the URL of the tweet
     *
     * @return string|null
     */
    public function getUrl()
    {
        $tweetId = $this->getRemoteId();
        $tweetUserScreenName = $this->getUserScreenName();

        if($tweetId && $tweetUserScreenName)
        {
            return 'https://twitter.com/'.$tweetUserScreenName.'/status/'.$tweetId;
        }
    }

    public function getTweetData()
    {
        if(!$this->data)
        {
            $this->data = $this->getRemoteTweetData();
        }

        return $this->data;
    }

    public function getAttributes($names = null, $flattenValues = false)
    {
        return parent::getAttributes($names, $flattenValues);
    }

    public function getAttribute($name, $flattenValue = false)
    {
        return parent::getAttribute($name, $flattenValue);
    }

    // Protected Methods
    // =========================================================================

    /**
     * Define Attributes
     */
    protected function defineAttributes()
    {
        $attributes = array(
            'remoteId' => AttributeType::Number,
            'data' => AttributeType::Mixed,
        );

        return $attributes;
    }

    // Private Methods
    // =========================================================================

    /**
     * Returns the API's data for a tweet
     *
     * @return array|null
     */
    private function getRemoteTweetData()
    {
        if(!empty($this->remoteId))
        {
            return craft()->twitter_api->getTweetById($this->remoteId);
        }
    }
}