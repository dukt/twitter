<?php
/**
 * @link      https://dukt.net/twitter/
 * @copyright Copyright (c) 2018, Dukt
 * @license   https://github.com/dukt/twitter/blob/master/LICENSE.md
 */

namespace dukt\twitter\controllers;

use Craft;
use craft\helpers\Json;
use craft\web\Controller;
use dukt\twitter\errors\InvalidTweetException;
use dukt\twitter\Plugin;
use GuzzleHttp\Exception\GuzzleException;
use yii\web\Response;

/**
 * API controller
 *
 * @author Dukt <support@dukt.net>
 * @since  3.0
 */
class FieldsController extends Controller
{
    // Public Methods
    // =========================================================================

    /**
     * Tweet field preview.
     *
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \yii\base\Exception
     */
    public function actionTweetFieldPreview(): Response
    {
        $tweetId = Craft::$app->getRequest()->getParam('id');

        try {
            $tweet = Plugin::getInstance()->getApi()->getTweet($tweetId);

            if (!$tweet) {
                throw new InvalidTweetException('No status found with that ID.');
            }

            $html = Craft::$app->getView()->renderTemplate('twitter/_components/tweet', [
                'tweet' => $tweet
            ]);

            return $this->asJson([
                'html' => $html,
            ]);
        } catch (GuzzleException $e) {
            $data = Json::decodeIfJson($e->getResponse()->getBody()->getContents());

            if (isset($data['errors'][0]['message'])) {
                return $this->asErrorJson($data['errors'][0]['message']);
            }

            Craft::error('Couldn’ load tweet preview: '.$e->getTraceAsString(), __METHOD__);

            return $this->asErrorJson($e->getMessage());
        } catch (InvalidTweetException $e) {
            Craft::error('Couldn’ load tweet preview: '.$e->getTraceAsString(), __METHOD__);

            return $this->asErrorJson($e->getMessage());
        }
    }
}
