<?php
/**
 * @link      https://dukt.net/craft/twitter/
 * @copyright Copyright (c) 2017, Dukt
 * @license   https://dukt.net/craft/twitter/docs/license
 */

namespace dukt\twitter\controllers;

use Craft;
use craft\web\Controller;
use dukt\twitter\Plugin as Twitter;

/**
 * Settings controller
 *
 * @author Dukt <support@dukt.net>
 * @since  3.0
 */
class SettingsController extends Controller
{
    // Public Methods
    // =========================================================================

    /**
     * Settings Index
     *
     * @return null
     */
    public function actionIndex()
    {
        $tokenExists = false;
        $resourceOwner = null;

        $token = Twitter::$plugin->getOauth()->getToken();

        if($token)
        {
            $tokenExists = true;

            // Retrieve resource owner’s details
            $provider = Twitter::$plugin->getOauth()->getOauthProvider();
            $resourceOwner = $provider->getUserDetails($token);
        }

        $oauthClientCredentials = Craft::$app->getConfig()->get('oauthClientCredentials', 'twitter');

        return $this->renderTemplate('twitter/settings', [
            'tokenExists' => $tokenExists,
            'resourceOwner' => $resourceOwner,
            'javascriptOrigin' => Twitter::$plugin->getOauth()->getJavascriptOrigin(),
            'redirectUri' => Twitter::$plugin->getOauth()->getRedirectUri(),
            'oauthClientCredentials' => $oauthClientCredentials,
        ]);
    }
}
