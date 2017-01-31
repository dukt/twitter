<?php
/**
 * @link      https://dukt.net/craft/twitter/
 * @copyright Copyright (c) 2017, Dukt
 * @license   https://dukt.net/craft/twitter/docs/license
 */

namespace dukt\twitter\controllers;

use Craft;
use craft\web\Controller;

/**
 * Twitter controller
 */
class CpController extends Controller
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
		$tweetId = Craft::$app->request->getParam('id');

		try {
            $tweet = \dukt\twitter\Plugin::getInstance()->twitter_api->getTweetById($tweetId);

            return $this->asJson($tweet);
        }
        catch(\Exception $e)
        {
            return $this->asErrorJson($e->getMessage());
        }
	}
}
