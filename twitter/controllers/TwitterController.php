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
        // redirect
        $redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;

        // session vars
        craft()->oauth->sessionClean();
        craft()->httpSession->add('oauth.plugin', 'twitter');
        craft()->httpSession->add('oauth.redirect', $redirect);
        craft()->httpSession->add('oauth.scopes', $this->scopes);
        craft()->httpSession->add('oauth.params', $this->params);

        // redirect

        $this->redirect(UrlHelper::getActionUrl('oauth/public/connect/', array(
            'provider' => $this->handle
        )));
    }

    /**
     * Disconnect
     */
    public function actionDisconnect()
    {
        // get plugin settings
        $plugin = craft()->plugins->getPlugin('twitter');
        $settings = $plugin->getSettings();

        // remove token from plugin settings
        $settings['token'] = null;
        craft()->plugins->savePluginSettings($plugin, $settings);

        // set notice
        craft()->userSession->setNotice(Craft::t("Disconnected from Twitter."));

        // redirect
        $redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;
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