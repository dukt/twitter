<?php
/**
 * @link      https://dukt.net/craft/twitter/
 * @copyright Copyright (c) 2018, Dukt
 * @license   https://dukt.net/craft/twitter/docs/license
 */

namespace dukt\twitter\controllers;

use Craft;
use craft\web\Controller;
use dukt\twitter\Plugin as Twitter;
use Exception;
use yii\web\Response;

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
     * Connect.
     *
     * @return Response
     */
    public function actionConnect(): Response
    {
        // Oauth provider
        $provider = Twitter::$plugin->getOauth()->getOauthProvider();

        // Obtain temporary credentials
        $temporaryCredentials = $provider->getTemporaryCredentials();

        // Store credentials in the session
        Craft::$app->getSession()->set('oauth.temporaryCredentials', $temporaryCredentials);

        // Redirect to login screen
        $authorizationUrl = $provider->getAuthorizationUrl($temporaryCredentials);

        return $this->redirect($authorizationUrl);
    }

    /**
     * Callback.
     *
     * @return Response
     */
    public function actionCallback(): Response
    {
        $provider = Twitter::$plugin->getOauth()->getOauthProvider();

        $oauthToken = Craft::$app->getRequest()->getParam('oauth_token');
        $oauthVerifier = Craft::$app->getRequest()->getParam('oauth_verifier');

        try {
            // Retrieve the temporary credentials we saved before.
            $temporaryCredentials = Craft::$app->getSession()->get('oauth.temporaryCredentials');

            // Obtain token credentials from the server.
            $tokenCredentials = $provider->getTokenCredentials($temporaryCredentials, $oauthToken, $oauthVerifier);

            // Save token
            Twitter::$plugin->getOauth()->saveToken($tokenCredentials);

            // Reset session variables

            // Redirect
            Craft::$app->getSession()->setNotice(Craft::t('twitter', "Connected to Twitter."));
        } catch (Exception $e) {
            // Failed to get the token credentials or user details.
            Craft::$app->getSession()->setError($e->getMessage());
        }

        return $this->redirect('twitter/settings');
    }

    /**
     * Disconnect
     *
     * @return Response
     */
    public function actionDisconnect(): Response
    {
        if (Twitter::$plugin->getOauth()->deleteToken()) {
            Craft::$app->getSession()->setNotice(Craft::t('twitter', "Disconnected from Twitter."));
        } else {
            Craft::$app->getSession()->setError(Craft::t('twitter', "Couldn’t disconnect from Twitter"));
        }

        $referer = Craft::$app->getRequest()->referrer;

        return $this->redirect($referer);
    }
}
