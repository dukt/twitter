<?php
/**
 * @link      https://dukt.net/craft/twitter/
 * @copyright Copyright (c) 2016, Dukt
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

    // Public Methods
    // =========================================================================

    /**
     * Connect
     *
     * @return null
     */
    public function actionConnect()
    {
        // Referer

        $referer = craft()->httpSession->get('twitter.referer');

        if (!$referer)
        {
            $referer = craft()->request->getUrlReferrer();

            craft()->httpSession->add('twitter.referer', $referer);

            TwitterPlugin::log('Twitter OAuth Connect Step 1: '."\r\n".print_r(['referer' => $referer], true), LogLevel::Info);
        }


        // Connect

        if ($response = craft()->oauth->connect(array(
            'plugin'   => 'twitter',
            'provider' => $this->handle
        )))
        {
            if ($response['success'])
            {
                $token = $response['token'];

                craft()->twitter_oauth->saveToken($token);

                TwitterPlugin::log('Twitter OAuth Connect Step 2: '."\r\n".print_r(['token' => $token], true), LogLevel::Info);

                craft()->userSession->setNotice(Craft::t("Connected to Twitter."));
            }
            else
            {
                craft()->userSession->setError(Craft::t($response['errorMsg']));
            }
        }
        else
        {
            craft()->userSession->setError(Craft::t("Couldn’t connect"));
        }

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

        $referer = craft()->request->getUrlReferrer();

        $this->redirect($referer);
    }
}