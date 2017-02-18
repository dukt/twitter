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
use Exception;

/**
 * OAuth controller
 *
 * @author Dukt <support@dukt.net>
 * @since  3.0
 */
class OauthController extends Controller
{
    // Public Methods
    // =========================================================================

    /**
     * Connect
     *
     * @return null
     */
    public function actionConnect()
    {
        // Oauth provider
        $provider = Twitter::$plugin->twitter_oauth->getOauthProvider();

        // Obtain temporary credentials
        $temporaryCredentials = $provider->getTemporaryCredentials();

        // Store credentials in the session
        Craft::$app->getSession()->set('oauth.temporaryCredentials', $temporaryCredentials);

        // Redirect to login screen
        $authorizationUrl = $provider->getAuthorizationUrl($temporaryCredentials);

        return $this->redirect($authorizationUrl);
    }

    /**
     * Disconnect
     *
     * @return null
     */
    public function actionDisconnect()
    {
        if (Twitter::$plugin->twitter_oauth->deleteToken())
        {
            Craft::$app->getSession()->setNotice(Craft::t('twitter', "Disconnected from Twitter."));
        }
        else
        {
            Craft::$app->getSession()->setError(Craft::t('twitter', "Couldn’t disconnect from Twitter"));
        }

        $referer = Craft::$app->request->referrer;

        return $this->redirect($referer);
    }

    /**
     * Callback
     *
     * @return null
     */
    public function actionCallback()
    {
        $provider = Twitter::$plugin->twitter_oauth->getOauthProvider();

        $oauthToken = Craft::$app->request->getParam('oauth_token');
        $oauthVerifier = Craft::$app->request->getParam('oauth_verifier');

        try {
            // Retrieve the temporary credentials we saved before.
            $temporaryCredentials = Craft::$app->getSession()->get('oauth.temporaryCredentials');

            // Obtain token credentials from the server.
            $tokenCredentials = $provider->getTokenCredentials($temporaryCredentials, $oauthToken, $oauthVerifier);

            // Save token
            Twitter::$plugin->twitter_oauth->saveToken($tokenCredentials);

            // Reset session variables

            // Redirect
            Craft::$app->getSession()->setNotice(Craft::t('twitter', "Connected to Twitter."));

        } catch (Exception $e) {
            // Failed to get the token credentials or user details.
            Craft::$app->getSession()->setError($e->getMessage());
        }

        return $this->redirect('twitter/settings');
    }
}