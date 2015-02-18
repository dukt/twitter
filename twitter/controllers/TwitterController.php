<?php

/**
 * Twitter plugin for Craft CMS
 *
 * @package   Twitter
 * @author    Benjamin David
 * @copyright Copyright (c) 2014, Dukt
 * @link      https://dukt.net/craft/twitter/
 * @license   https://dukt.net/craft/twitter/docs/license
 */

namespace Craft;

/**
 * Twitter controller
 */
class TwitterController extends BaseController
{
	private $handle = 'twitter';
    private $scopes = array();
    private $params = array();

    /**
     * Connect
     */
    public function actionConnect(array $variables = array())
    {
        // referer

        $referer = craft()->httpSession->get('twitter.referer');

        if(!$referer)
        {
            $referer = craft()->request->getUrlReferrer();

            craft()->httpSession->add('twitter.referer', $referer);
        }


        // connect

        if($response = craft()->oauth->connect(array(
            'plugin' => 'twitter',
            'provider' => 'twitter',
            'scopes' => $this->scopes,
            'params' => $this->params
        )))
        {
            if($response['success'])
            {
                // token
                $token = $response['token'];

                // save token
                craft()->twitter->saveToken($token);

                // session notice
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



        // redirect

        craft()->httpSession->remove('twitter.referer');

        $this->redirect($referer);
    }

    /**
     * Disconnect
     */
    public function actionDisconnect()
    {
        if(craft()->twitter->deleteToken())
        {
            craft()->userSession->setNotice(Craft::t("Disconnected from Twitter."));
        }
        else
        {
            craft()->userSession->setNotice(Craft::t("Couldn’t disconnect from Twitter."));
        }

        // redirect
        $redirect = craft()->request->getUrlReferrer();
        $this->redirect($redirect);
    }


	/**
	 * Looks up a tweet by its ID.
	 */
	public function actionLookupTweet()
	{
		$this->requireAjaxRequest();

		$tweetId = craft()->request->getParam('id');
		$tweet = craft()->twitter->getTweetById($tweetId);
		$this->returnJson($tweet);
	}
}