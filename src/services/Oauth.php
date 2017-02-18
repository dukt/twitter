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
    public function saveToken(Token $token)
    {
        // get plugin
        $plugin = Craft::$app->plugins->getPlugin('twitter');

        // get settings
        $settings = $plugin->getSettings();


        // do we have an existing token ?

        $existingToken = \dukt\oauth\Plugin::getInstance()->oauth->getTokenById($settings->tokenId);

        if($existingToken)
        {
            $token->id = $existingToken->id;
        }

        // save token
        \dukt\oauth\Plugin::getInstance()->oauth->saveToken($token);

        // set token ID
        $settings->tokenId = $token->id;

        // save plugin settings
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
        // get plugin
        $plugin = Craft::$app->plugins->getPlugin('twitter');

        // get settings
        $settings = $plugin->getSettings();

        if($settings->tokenId)
        {
            $token = \dukt\oauth\Plugin::getInstance()->oauth->getTokenById($settings->tokenId);

            if($token)
            {
                if(\dukt\oauth\Plugin::getInstance()->oauth->deleteToken($token))
                {
                    $settings->tokenId = null;

                    Craft::$app->plugins->savePluginSettings($plugin, $settings->getAttributes());

                    return true;
                }
            }
        }

        return false;
    }
}