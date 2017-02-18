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
use dukt\oauth\Plugin as Oauth;
use Exception;

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

        $token = Twitter::$plugin->twitter_oauth->getToken();

        if($token)
        {
            $tokenExists = true;

            // Retrieve resource owner’s details
            $provider = Twitter::$plugin->twitter_oauth->getOauthProvider();
            $resourceOwner = $provider->getUserDetails($token);
        }

        return $this->renderTemplate('twitter/settings', [
            'tokenExists' => $tokenExists,
            'resourceOwner' => $resourceOwner,
        ]);
    }
}
