<?php
/**
 * @link      https://dukt.net/craft/twitter/
 * @copyright Copyright (c) 2015, Dukt
 * @license   https://dukt.net/craft/twitter/docs/license
 */

namespace Craft;

/**
 * Twitter OAuth controller
 */
class Twitter_OauthController extends BaseController
{
    // Properties
    // =========================================================================

    /**
     * @var string
     */
    private $handle = 'twitter';

    /**
     * @var array
     */
    private $scopes = array();

    /**
     * @var array
     */
    private $params = array();

    // Public Methods
    // =========================================================================

    /**
     * Connect
     *
     * @return null
     */
    public function actionConnect()
    {
        // craft()->oauth->sessionClean();
        // craft()->httpSession->remove('twitter.referer');
        // unset($_SESSION['token_credentials']);
        // unset($_SESSION['temporary_credentials']);
        // unset($_SESSION['token_credentials']);

        // referer

        $referer = craft()->httpSession->get('twitter.referer');

        if (!$referer)
        {
            $referer = craft()->request->getUrlReferrer();

            craft()->httpSession->add('twitter.referer', $referer);

            TwitterHelper::log('Twitter OAuth Connect Step 1: '."\r\n".print_r(['referer' => $referer], true), LogLevel::Info);
        }


        // connect

        if ($response = craft()->oauth->connect(array(
            'plugin'   => 'twitter',
            'provider' => $this->handle,
            'scopes'   => $this->scopes,
            'params'   => $this->params
        )))
        {
            // var_dump($response);
            // die();
            if ($response['success'])
            {
                // token
                $token = $response['token'];

                // save token
                craft()->twitter_oauth->saveToken($token);

                TwitterHelper::log('Twitter OAuth Connect Step 2: '."\r\n".print_r(['token' => $token], true), LogLevel::Info);

                // session notice
                craft()->userSession->setNotice(Craft::t("Connected to Twitter."));
            }
            else
            {
                // session error
                craft()->userSession->setError(Craft::t($response['errorMsg']));
            }
        }
        else
        {
            // session error
            craft()->userSession->setError(Craft::t("Couldn’t connect"));
        }

        // OAuth Step 5

        // redirect

        craft()->httpSession->remove('twitter.referer');

        $this->redirect($referer);
    }

    /**
     * Disconnect
     *
     * @return null
     */
    public function actionDisconnect()
    {
        if (craft()->twitter_oauth->deleteToken())
        {
            craft()->userSession->setNotice(Craft::t("Disconnected from Twitter."));
        }
        else
        {
            craft()->userSession->setError(Craft::t("Couldn’t disconnect from Twitter"));
        }

        // redirect
        $redirect = craft()->request->getUrlReferrer();
        $this->redirect($redirect);
    }
}