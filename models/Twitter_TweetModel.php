<?php
/**
 * @link      https://dukt.net/craft/twitter/
 * @copyright Copyright (c) 2017, Dukt
 * @license   https://dukt.net/craft/twitter/docs/license
 */

namespace Craft;

class Twitter_TweetModel extends BaseModel
{
    private $data;

    // Public Methods
    // =========================================================================

    public function getRemoteId()
    {
        return $this->remoteId;
    }

    public function getText()
    {
        $tweetData = $this->getRemoteTweetData();

        if(!empty($tweetData['text']))
        {
            return $tweetData['text'];
        }
    }

    public function getUserId()
    {
        $tweetData = $this->getRemoteTweetData();

        if(!empty($tweetData['user']['id']))
        {
            return $tweetData['user']['id'];
        }
    }

    public function getUserName()
    {
        $tweetData = $this->getRemoteTweetData();

        if(!empty($tweetData['user']['name']))
        {
            return $tweetData['user']['name'];
        }
    }

    public function getUserProfileImageUrl($size = null)
    {
        if (craft()->request->isSecureConnection())
        {
            $imageUrl = $this->getUserProfileImageSecureUrl();
        }
        else
        {
            $imageUrl = $this->getUserProfileImageUnsecureUrl();
        }

        if($size)
        {
            return str_replace("_normal.", "_".$size.".", $imageUrl);
        }
    }

    public function getUserProfileImageUnsecureUrl()
    {
        $tweetData = $this->getRemoteTweetData();

        if(!empty($tweetData['user']['profile_image_url']))
        {
            return $tweetData['user']['profile_image_url'];
        }
    }

    public function getUserProfileImageSecureUrl()
    {
        $tweetData = $this->getRemoteTweetData();

        if(!empty($tweetData['user']['profile_image_url_https']))
        {
            return $tweetData['user']['profile_image_url_https'];
        }
    }

    public function getUserScreenName()
    {
        $tweetData = $this->getRemoteTweetData();

        if(!empty($tweetData['user']['screen_name']))
        {
            return $tweetData['user']['screen_name'];
        }
    }

    public function getCreatedAt()
    {
        $tweetData = $this->getRemoteTweetData();

        if(!empty($tweetData['created_at']))
        {
            return $tweetData['created_at'];
        }
    }

    public function getUser()
    {
        $tweetData = $this->getRemoteTweetData();

        if(!empty($tweetData['user']))
        {
            return $tweetData['user'];
        }
    }

    public function getUrl()
    {
        $tweetId = $this->getRemoteId();
        $tweetUserScreenName = $this->getUserScreenName();

        if($tweetId && $tweetUserScreenName)
        {
            return 'https://twitter.com/'.$tweetUserScreenName.'/status/'.$tweetId;
        }
    }

    public function getRemoteTweetData()
    {
        if(!$this->data)
        {
            $this->data = craft()->twitter->getTweetById($this->remoteId);
        }

        return $this->data;
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
        );

        return $attributes;
    }
}