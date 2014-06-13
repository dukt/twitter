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
    public function actionConnect()
    {
        craft()->oauth->connect(array(
            'plugin' => 'twitter',
            'provider' => 'twitter',
            'scopes' => $this->scopes,
            'params' => $this->params
        ));
    }

    /**
     * Disconnect
     */
    public function actionDisconnect()
    {
        // reset token
        craft()->twitter->saveToken(null);

        // set notice
        craft()->userSession->setNotice(Craft::t("Disconnected from Twitter."));

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