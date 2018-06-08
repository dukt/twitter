<?php
/**
 * @link      https://dukt.net/craft/twitter/
 * @copyright Copyright (c) 2018, Dukt
 * @license   https://dukt.net/craft/twitter/docs/license
 */

namespace dukt\twitter\models;

use craft\base\Model;
use dukt\twitter\helpers\TwitterHelper;

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
     * @var getCreatedAt() $this->data['created_at']
     */
    public $createdAt;

    /**
     * @var
     */
    public $data;

    /**
     * @var getText() $this->data['full_text']
     */
    public $text;

    /**
     * @var
     */
    public $remoteId;

    /**
     * @var getUserId() $this->data['user']['id']
     */
    public $remoteUserId;

    /**
     * @var getUserName() $this->data['user']['name']
     */
    public $username;

    /**
     * @var getUserProfileRemoteImageSecureUrl() $this->data['user']['profile_image_url_https']
     */
    public $userProfileRemoteImageSecureUrl;

    /**
     * @var getUserProfileRemoteImageUrl() $this->data['user']['profile_image_url']
     */
    public $userProfileRemoteImageUrl;

    /**
     * @var getUserScreenName() $this->data['user']['screen_name']
     */
    public $userScreenName;

    // Public Methods
    // =========================================================================

    /**
     * Returns the URL of the tweet.
     *
     * @return string|null
     */
    public function getUrl()
    {
        if ($this->remoteId && $this->userScreenName) {
            return 'https://twitter.com/'.$this->userScreenName.'/status/'.$this->remoteId;
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
        return TwitterHelper::getUserProfileImageResourceUrl($this->remoteUserId, $size);
    }

    /**
     * Returns the tweet's author user profile URL.
     *
     * @return mixed
     */
    public function getUserProfileUrl()
    {
        return 'https://twitter.com/'.$this->userScreenName;
    }
}
