<?php
/**
 * @link      https://dukt.net/twitter/
 * @copyright Copyright (c) Dukt
 * @license   https://github.com/dukt/twitter/blob/master/LICENSE.md
 */

namespace dukt\twitter\controllers;

use Craft;
use craft\web\Controller;
use dukt\twitter\Plugin;
use League\OAuth1\Client\Credentials\CredentialsException;
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
        $provider = Plugin::getInstance()->getOauth()->getOauthProvider();

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
        $provider = Plugin::getInstance()->getOauth()->getOauthProvider();
        $oauthToken = Craft::$app->getRequest()->getParam('oauth_token');
        $oauthVerifier = Craft::$app->getRequest()->getParam('oauth_verifier');

        try {
            // Retrieve the temporary credentials we saved before.
            $temporaryCredentials = Craft::$app->getSession()->get('oauth.temporaryCredentials');

            // Obtain token credentials from the server.
            $tokenCredentials = $provider->getTokenCredentials($temporaryCredentials, $oauthToken, $oauthVerifier);

            // Save token
            Plugin::getInstance()->getOauth()->saveToken($tokenCredentials);

            // Redirect
            Craft::$app->getSession()->setNotice(Craft::t('twitter', 'Connected to Twitter.'));
        } catch (CredentialsException $credentialsException) {
            // Failed to get the token credentials or user details.
            Craft::error('Couldn’t connect to Twitter: '.$credentialsException->getTraceAsString(), __METHOD__);
            Craft::$app->getSession()->setError($credentialsException->getMessage());
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
        if (Plugin::getInstance()->getOauth()->deleteToken()) {
            Craft::$app->getSession()->setNotice(Craft::t('twitter', 'Disconnected from Twitter.'));
        } else {
            Craft::$app->getSession()->setError(Craft::t('twitter', 'Couldn’t disconnect from Twitter'));
        }

        $referer = Craft::$app->getRequest()->referrer;

        return $this->redirect($referer);
    }
}
