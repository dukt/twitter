<?php
/**
 * @link      https://dukt.net/craft/twitter/
 * @copyright Copyright (c) 2017, Dukt
 * @license   https://dukt.net/craft/twitter/docs/license
 */

namespace dukt\twitter\services;

use Craft;
use yii\base\Component;
use dukt\oauth\models\Token;
use League\OAuth1\Client\Credentials\TokenCredentials;
use craft\helpers\UrlHelper;

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
     * @var Token|null
     */
    private $token;

    // Public Methods
    // =========================================================================

    /**
     * Save Token
     *
     * @param Token $token
     */
    public function saveToken(TokenCredentials $token)
    {
        // Save token and token secret in the plugin's settings

        $plugin = Craft::$app->plugins->getPlugin('twitter');

        $settings = $plugin->getSettings();
        $settings->token = $token->getIdentifier();
        $settings->tokenSecret = $token->getSecret();

        Craft::$app->plugins->savePluginSettings($plugin, $settings->getAttributes());
    }

    /**
     * Get OAuth Token
     *
     * @return Token|null
     */
    public function getToken()
    {
        if($this->token)
        {
            return $this->token;
        }
        else
        {
            $plugin = Craft::$app->plugins->getPlugin('twitter');
            $settings = $plugin->getSettings();

            if($settings->token && $settings->tokenSecret) {
                $token = new \League\OAuth1\Client\Credentials\TokenCredentials();
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
        $plugin = Craft::$app->plugins->getPlugin('twitter');

        $settings = $plugin->getSettings();
        $settings->token = null;
        $settings->tokenSecret = null;

        Craft::$app->plugins->savePluginSettings($plugin, $settings->getAttributes());

        return true;
    }

    public function getOauthProvider()
    {
        $options = Craft::$app->config->get('oauthClientOptions', 'twitter');

        if(!isset($options['callback_uri']))
        {
            $options['callback_uri'] = UrlHelper::actionUrl('twitter/oauth/callback');
        }

        return new \League\OAuth1\Client\Server\Twitter($options);
    }
}