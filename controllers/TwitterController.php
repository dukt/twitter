<?php
/**
 * @link      https://dukt.net/craft/twitter/
 * @copyright Copyright (c) 2016, Dukt
 * @license   https://dukt.net/craft/twitter/docs/license
 */

namespace Craft;

/**
 * Twitter controller
 */
class TwitterController extends BaseController
{
    // Public Methods
    // =========================================================================

	/**
	 * Looks up a tweet by its ID.
     *
     * @return null
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
        craft()->twitter_plugin->requireDependencies();

        $plugin = craft()->plugins->getPlugin('twitter');

        $variables = array(
            'provider' => false,
            'account' => false,
            'token' => false,
            'error' => false
        );

        $provider = craft()->oauth->getProvider('twitter');

        if ($provider && $provider->isConfigured())
        {
            $token = craft()->twitter_oauth->getToken();

            if ($token)
            {
                try
                {
                    $account = craft()->twitter_cache->get(['getAccount', $token]);

                    if(!$account)
                    {
                        $account = $provider->getAccount($token);
                        craft()->twitter_cache->set(['getAccount', $token], $account);
                    }

                    if ($account)
                    {
                        TwitterPlugin::log("Twitter OAuth Account:\r\n".print_r($account, true), LogLevel::Info);

                        $variables['account'] = $account;
                        $variables['settings'] = $plugin->getSettings();
                    }
                }
                catch(\Exception $e)
                {
                    if(method_exists($e, 'getResponse'))
                    {
                            TwitterPlugin::log("Couldn’t get account: ".$e->getResponse(), LogLevel::Error);
                    }
                    else
                    {
                        TwitterPlugin::log("Couldn’t get account: ".$e->getMessage(), LogLevel::Error);
                    }

                    $variables['error'] = $e->getMessage();
                }
            }

            $variables['token'] = $token;

            $variables['provider'] = $provider;
        }

        $this->renderTemplate('twitter/settings', $variables);
    }
}