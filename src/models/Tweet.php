<?php
/**
 * @link      https://dukt.net/twitter/
 * @copyright Copyright (c) Dukt
 * @license   https://github.com/dukt/twitter/blob/master/LICENSE.md
 */

namespace dukt\twitter\models;

use craft\base\Model;
use craft\errors\ImageException;
use dukt\twitter\helpers\TwitterHelper;
use GuzzleHttp\Exception\GuzzleException;

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
     * @var \DateTime The date the tweet was created at.
     */
    public $createdAt;

    /**
     * @var array Raw tweet data.
     */
    public array $data;

    /**
     * @var String The tweet’s text.
     */
    public $text;

    /**
     * @var String The tweet’s ID.
     */
    public $remoteId;

    /**
     * @var String The tweet’s author user ID.
     */
    public $remoteUserId;

    /**
     * @var string The author user’s username.
     */
    public string $username;

    /**
     * @var string The author user’s profile remote image secure URL.
     */
    public string $userProfileRemoteImageSecureUrl;

    /**
     * @var string The author user’s profile remote image URL.
     */
    public string $userProfileRemoteImageUrl;

    /**
     * @var string The author user’s screen name.
     */
    public string $userScreenName;

    // Public Methods
    // =========================================================================

    /**
     * Returns the URL of the tweet.
     *
     * @return string|null
     */
    public function getUrl()
    {
        if (!$this->remoteId || !$this->userScreenName) {
            return null;
        }

        return 'https://twitter.com/'.$this->userScreenName.'/status/'.$this->remoteId;
    }

    /**
     * Returns the tweet's author user profile image resource url.
     *
     *
     * @return null|string
     * @throws GuzzleException
     * @throws ImageException
     * @throws \yii\base\Exception
     */
    public function getUserProfileImageUrl(int $size = 48)
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
