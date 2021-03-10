<?php
/**
 * @link      https://dukt.net/twitter/
 * @copyright Copyright (c) 2021, Dukt
 * @license   https://github.com/dukt/twitter/blob/master/LICENSE.md
 */

namespace dukt\twitter\services;

use Craft;
use dukt\twitter\errors\InvalidAccountException;
use dukt\twitter\Plugin;
use yii\base\Component;
use League\OAuth1\Client\Credentials\TokenCredentials;
use craft\helpers\UrlHelper;
use \League\OAuth1\Client\Server\Twitter as TwitterProvider;

/**
 * OAuth Service
 *
 * @author Dukt <support@dukt.net>
 * @since  3.0
 */
class Oauth extends Component
{
    // Properties
    // =========================================================================

    /**
     * @var TokenCredentials|null
     */
    private $token;

    // Public Methods
    // =========================================================================

    /**
     * Saves a token
     *
     * @param TokenCredentials $token
     * @return bool
     * @throws InvalidAccountException
     * @throws \yii\db\Exception
     */
    public function saveToken(TokenCredentials $token)
    {
        $account = Plugin::$plugin->getAccounts()->getAccount();
        $account->token = $token->getIdentifier();
        $account->tokenSecret = $token->getSecret();

        return Plugin::$plugin->getAccounts()->saveAccount($account);
    }

    /**
     * Gets a token
     *
     * @return TokenCredentials|null
     */
    public function getToken()
    {
        $account = Plugin::$plugin->getAccounts()->getAccount();

        if (!$account || !$account->token || !$account->tokenSecret) {
            return null;
        }

        $token = new TokenCredentials();
        $token->setIdentifier($account->token);
        $token->setSecret($account->tokenSecret);

        return $token;
    }

    /**
     * Deletes a token
     *
     * @return bool
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function deleteToken()
    {
        $account = Plugin::$plugin->getAccounts()->getAccount();

        return Plugin::$plugin->getAccounts()->deleteAccount($account);
    }

    /**
     * Returns a Twitter provider (server) object.
     *
     * @return TwitterProvider
     */
    public function getOauthProvider()
    {
        $options = [
            'identifier' => Plugin::getInstance()->getConsumerKey(),
            'secret' => Craft::parseEnv(Plugin::getInstance()->getConsumerSecret()),
        ];

        if (!isset($options['callback_uri'])) {
            $options['callback_uri'] = UrlHelper::actionUrl('twitter/oauth/callback');
        }

        return new TwitterProvider($options);
    }

    /**
     * Gets the javascript origin
     *
     * @return string
     * @throws \craft\errors\SiteNotFoundException
     */
    public function getJavascriptOrigin()
    {
        return UrlHelper::baseUrl();
    }

    /**
     * Get redirect URI
     *
     * @return string
     */
    public function getRedirectUri()
    {
        return UrlHelper::actionUrl('twitter/oauth/callback');
    }
}