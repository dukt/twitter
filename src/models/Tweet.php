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
     * @var The date the tweet was created at.
     */
    public $createdAt;

    /**
     * @var Raw tweet data.
     */
    public $data;

    /**
     * @var The tweet’s text.
     */
    public $text;

    /**
     * @var The tweet’s ID.
     */
    public $remoteId;

    /**
     * @var The tweet’s author user ID.
     */
    public $remoteUserId;

    /**
     * @var The author user’s username.
     */
    public $username;

    /**
     * @var The author user’s profile remote image secure URL.
     */
    public $userProfileRemoteImageSecureUrl;

    /**
     * @var The author user’s profile remote image URL.
     */
    public $userProfileRemoteImageUrl;

    /**
     * @var The author user’s screen name.
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
