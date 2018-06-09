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
use Exception;

/**
 * Settings controller
 *
 * @author Dukt <support@dukt.net>
 * @since  3.0
 */
class SettingsController extends Controller
{
    // Public Methods
    // =========================================================================

    /**
     * Settings index.
     *
     * @return Response
     * @throws \craft\errors\SiteNotFoundException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionIndex(): Response
    {
        $tokenExists = false;
        $resourceOwner = null;

        try {
            $token = Twitter::$plugin->getOauth()->getToken();

            if ($token) {
                $tokenExists = true;

                // Retrieve resource owner’s details
                $provider = Twitter::$plugin->getOauth()->getOauthProvider();
                $resourceOwner = $provider->getUserDetails($token);
            }
        } catch (Exception $e) {
            Craft::error('Couldn’t retrieve twitter account: '.$e->getTraceAsString(), __METHOD__);
            $error = $e->getMessage();
        }

        return $this->renderTemplate('twitter/settings', [
            'error' => isset($error) ? $error : null,
            'tokenExists' => $tokenExists,
            'resourceOwner' => $resourceOwner,
            'javascriptOrigin' => Twitter::$plugin->getOauth()->getJavascriptOrigin(),
            'redirectUri' => Twitter::$plugin->getOauth()->getRedirectUri(),
        ]);
    }

    /**
     * OAuth settings.
     *
     * @return Response
     * @throws \craft\errors\SiteNotFoundException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionOauth(): Response
    {
        $plugin = Craft::$app->getPlugins()->getPlugin('twitter');

        return $this->renderTemplate('twitter/settings/oauth', [
            'javascriptOrigin' => Twitter::$plugin->getOauth()->getJavascriptOrigin(),
            'redirectUri' => Twitter::$plugin->getOauth()->getRedirectUri(),
            'settings' => $plugin->getSettings(),
        ]);
    }

    /**
     * Save OAuth settings.
     *
     * @return Response
     * @throws \yii\web\BadRequestHttpException
     */
    public function actionSaveOauthSettings(): Response
    {
        $plugin = Craft::$app->getPlugins()->getPlugin('twitter');
        $settings = $plugin->getSettings();

        $postSettings = Craft::$app->getRequest()->getBodyParam('settings');

        $settings['oauthConsumerKey'] = $postSettings['oauthConsumerKey'];
        $settings['oauthConsumerSecret'] = $postSettings['oauthConsumerSecret'];

        Craft::$app->getPlugins()->savePluginSettings($plugin, $settings->getAttributes());

        Craft::$app->getSession()->setNotice(Craft::t('twitter', 'OAuth settings saved.'));

        return $this->redirectToPostedUrl();
    }
}
