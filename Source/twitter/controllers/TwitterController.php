<?php
/**
 * @link      https://dukt.net/craft/twitter/
 * @copyright Copyright (c) 2015, Dukt
 * @license   https://dukt.net/craft/twitter/docs/license
 */

namespace Craft;

/**
 * Twitter controller
 */
class TwitterController extends BaseController
{
    // Properties
    // =========================================================================

	private $handle = 'twitter';
    private $scopes = array();
    private $params = array();

    // Public Methods
    // =========================================================================

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


    /**
     * Settings
     *
     * @return null
     */
    public function actionSettings()
    {
        $plugin = craft()->plugins->getPlugin('twitter');
        $pluginDependencies = $plugin->getPluginDependencies();

        if (count($pluginDependencies) > 0)
        {
            $this->renderTemplate('twitter/settings/_dependencies', ['pluginDependencies' => $pluginDependencies]);
        }
        else
        {
            if (isset(craft()->oauth))
            {

                // ----------------------------------------------------------

                $variables = array(
                    'provider' => false,
                    'account' => false,
                    'token' => false,
                    'error' => false
                );

                $provider = craft()->oauth->getProvider('twitter');

                if ($provider && $provider->isConfigured())
                {
                    $token = craft()->twitter->getToken();

                    if ($token)
                    {
                        $provider->setToken($token);

                        try
                        {
                            $account = $provider->getAccount();

                            if ($account)
                            {
                                $variables['account'] = $account;
                                $variables['settings'] = $plugin->getSettings();
                            }
                        }
                        catch(\Exception $e)
                        {
                            Craft::log('Couldn’t get account. '.$e->getMessage(), LogLevel::Error);

                            $variables['error'] = $e->getMessage();
                        }
                    }

                    $variables['token'] = $token;
                }

                $variables['provider'] = $provider;

                $this->renderTemplate('twitter/settings', $variables);

                // ----------------------------------------------------------
            }
            else
            {
                $this->renderTemplate('twitter/settings/_oauthNotInstalled');
            }
        }
    }
}