<?php
/**
 * @link      https://dukt.net/craft/twitter/
 * @copyright Copyright (c) 2017, Dukt
 * @license   https://dukt.net/craft/twitter/docs/license
 */

namespace dukt\twitter\controllers;

use Craft;
use craft\helpers\UrlHelper;
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
            $provider = $this->getOauthProvider();
            $resourceOwner = $provider->getUserDetails($token);
        }

        return $this->renderTemplate('twitter/settings', [
            'tokenExists' => $tokenExists,
            'resourceOwner' => $resourceOwner,
        ]);
    }

    /**
     * Connect
     *
     * @return null
     */
    public function actionOauthConnect()
    {
        // Oauth provider
        $provider = $this->getOauthProvider();

        // Obtain temporary credentials
        $temporaryCredentials = $provider->getTemporaryCredentials();

        // Store credentials in the session
        Craft::$app->getSession()->set('oauth.temporaryCredentials', $temporaryCredentials);

        // Redirect to login screen
        $authorizationUrl = $provider->getAuthorizationUrl($temporaryCredentials);
        header('Location: ' . $authorizationUrl);
        exit;
    }

    public function actionOauthCallback()
    {
        $provider = $this->getOauthProvider();

        $oauthToken = Craft::$app->request->getParam('oauth_token');
        $oauthVerifier = Craft::$app->request->getParam('oauth_verifier');

        try {
            // Retrieve the temporary credentials we saved before.
            $temporaryCredentials = Craft::$app->getSession()->get('oauth.temporaryCredentials');

            // Obtain token credentials from the server.
            $tokenCredentials = $provider->getTokenCredentials($temporaryCredentials, $oauthToken, $oauthVerifier);

            // Save token
            Twittter::$plugin->twitter_oauth->saveToken($tokenCredentials);

            // Reset session variables

            // Redirect
            return $this->redirect('twitter/settings');
        } catch (\League\OAuth1\Client\Exceptions\Exception $e) {
            // Failed to get the token credentials or user details.
            exit($e->getMessage());
        }
    }

    public function actionOauthDisconnect()
    {
        // Reset token and token secret in the plugin's settings
        $plugin = Craft::$app->plugins->getPlugin('twitter');
        $settings = $plugin->getSettings();
        $settings->token = null;
        $settings->tokenSecret = null;
        Craft::$app->plugins->savePluginSettings($plugin, $settings->getAttributes());

        return $this->redirect('twitter/settings');
    }

    private function getOauthProvider()
    {
        $options = Craft::$app->config->get('oauthClientOptions', 'twitter');

        if(!isset($options['callback_uri']))
        {
            $options['callback_uri'] = UrlHelper::actionUrl('twitter/settings/oauth-callback');
        }

        return new \League\OAuth1\Client\Server\Twitter($options);
    }
}
