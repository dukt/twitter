<?php
/**
 * @link      https://dukt.net/craft/twitter/
 * @copyright Copyright (c) 2017, Dukt
 * @license   https://dukt.net/craft/twitter/docs/license
 */

namespace dukt\twitter\services;

use Craft;
use dukt\twitter\Plugin as Twitter;
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
     * Save Token
     *
     * @param TokenCredentials $token
     */
    public function saveToken(TokenCredentials $token)
    {
        // Save token and token secret in the plugin's settings

        $plugin = Craft::$app->getPlugins()->getPlugin('twitter');

        $settings = $plugin->getSettings();
        $settings->token = $token->getIdentifier();
        $settings->tokenSecret = $token->getSecret();

        Craft::$app->getPlugins()->savePluginSettings($plugin, $settings->getAttributes());
    }

    /**
     * Get OAuth Token
     *
     * @return TokenCredentials|null
     */
    public function getToken()
    {
        if($this->token)
        {
            return $this->token;
        }
        else
        {
            $plugin = Craft::$app->getPlugins()->getPlugin('twitter');
            $settings = $plugin->getSettings();

            if($settings->token && $settings->tokenSecret) {
                $token = new TokenCredentials();
                $token->setIdentifier($settings->token);
                $token->setSecret($settings->tokenSecret);
                return $token;
            }

        }
    }

    /**
     * Delete Token
     *
     * @return bool
     */
    public function deleteToken()
    {
        $plugin = Craft::$app->getPlugins()->getPlugin('twitter');

        $settings = $plugin->getSettings();
        $settings->token = null;
        $settings->tokenSecret = null;

        Craft::$app->getPlugins()->savePluginSettings($plugin, $settings->getAttributes());

        return true;
    }

    /**
     * Returns a Twitter provider (server) object.
     *
     * @return TwitterProvider
     */
    public function getOauthProvider()
    {
        $oauthConsumerKey = Twitter::$plugin->getConsumerKey();
        $oauthConsumerSecret = Twitter::$plugin->getConsumerSecret();

        $options = [];

        $options['identifier'] = $oauthConsumerKey;
        $options['secret'] = $oauthConsumerSecret;

        if(!isset($options['callback_uri']))
        {
            $options['callback_uri'] = UrlHelper::actionUrl('twitter/oauth/callback');
        }

        return new TwitterProvider($options);
    }

    /**
     * Get javascript origin
     * @return string
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