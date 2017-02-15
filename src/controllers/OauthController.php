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
 * OAuth controller
 *
 * @author Dukt <support@dukt.net>
 * @since  3.0
 */
class OauthController extends Controller
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
        $session = Craft::$app->getSession();
        $referer = $session->get('twitter.referer');

        if (!$referer)
        {
            $referer = Craft::$app->request->referrer;

            $session->set('twitter.referer', $referer);

            Craft::trace('Twitter OAuth Connect (1): '."\r\n".print_r(['referer' => $referer], true), __METHOD__);
        }


        // Connect

        if ($response = \dukt\oauth\Plugin::getInstance()->oauth->connect(array(
            'plugin'   => 'twitter',
            'provider' => $this->handle
        )))
        {
            if($response && is_object($response) && !$response->data)
            {
                return $response;
            }

            if ($response['success'])
            {
                $token = $response['token'];

                Twitter::$plugin->twitter_oauth->saveToken($token);

                Craft::trace('Twitter OAuth Connect (2): '."\r\n".print_r(['token' => $token], true), __METHOD__);

                $session->setNotice(Craft::t('app', "Connected to Twitter."));
            }
            else
            {
                $session->setError(Craft::t('app', $response['errorMsg']));
            }
        }
        else
        {
            $session->setError(Craft::t('app', "Couldn’t connect"));
        }

        $session->remove('twitter.referer');

        return $this->redirect($referer);
    }

    /**
     * Disconnect
     *
     * @return null
     */
    public function actionDisconnect()
    {
        $session = Craft::$app->getSession();

        if (Twitter::$plugin->twitter_oauth->deleteToken())
        {
            $session->setNotice(Craft::t('app', "Disconnected from Twitter."));
        }
        else
        {
            $session->setError(Craft::t('app', "Couldn’t disconnect from Twitter"));
        }

        $referer = Craft::$app->request->referrer;

        return $this->redirect($referer);
    }
}