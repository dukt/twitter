<?php
namespace Craft;

/**
 * Twitter controller
 */
class TwitterController extends BaseController
{
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