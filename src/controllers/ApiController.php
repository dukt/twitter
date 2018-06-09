<?php
/**
 * @link      https://dukt.net/craft/twitter/
 * @copyright Copyright (c) 2018, Dukt
 * @license   https://dukt.net/craft/twitter/docs/license
 */

namespace dukt\twitter\controllers;

use Craft;
use craft\helpers\Json;
use craft\web\Controller;
use dukt\twitter\errors\InvalidTweetException;
use dukt\twitter\Plugin as Twitter;
use GuzzleHttp\Exception\ClientException;
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
     * Tweet field preview.
     *
     * @return Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Twig_Error_Loader
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function actionTweetFieldPreview(): Response
    {
        $tweetId = Craft::$app->getRequest()->getParam('id');

        try {
            $tweet = Twitter::$plugin->getApi()->getTweet($tweetId);

            if(!$tweet) {
                throw new InvalidTweetException('No status found with that ID.');
            }

            $html = Craft::$app->getView()->renderTemplate('twitter/_components/tweet', [
                'tweet' => $tweet
            ]);

            return $this->asJson([
                'html' => $html,
            ]);
        } catch (ClientException $e) {
            $data = Json::decodeIfJson($e->getResponse()->getBody()->getContents());

            if(isset($data['errors'][0]['message'])) {
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
