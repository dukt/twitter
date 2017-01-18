<?php
/**
 * @link      https://dukt.net/craft/twitter/
 * @copyright Copyright (c) 2017, Dukt
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
}
