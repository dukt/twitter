<?php
/**
 * @link      https://dukt.net/craft/twitter/
 * @copyright Copyright (c) 2018, Dukt
 * @license   https://dukt.net/craft/twitter/docs/license
 */

namespace dukt\twitter\controllers;

use Craft;
use craft\web\Controller;
use dukt\twitter\Plugin as Twitter;
use yii\web\Response;

/**
 * API controller
 *
 * @author Dukt <support@dukt.net>
 * @since  3.0
 */
class ApiController extends Controller
{
    // Public Methods
    // =========================================================================

    /**
     * Looks up a tweet by its ID.
     *
     * @return Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function actionLookupTweet(): Response
    {
        $tweetId = Craft::$app->getRequest()->getParam('id');

        try {
            $tweet = Twitter::$plugin->getApi()->getTweetById($tweetId);

            return $this->asJson($tweet);
        } catch (\Exception $e) {
            return $this->asErrorJson($e->getMessage());
        }
    }
}
