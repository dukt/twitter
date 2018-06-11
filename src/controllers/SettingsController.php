<?php
/**
 * @link      https://dukt.net/twitter/
 * @copyright Copyright (c) 2018, Dukt
 * @license   https://github.com/dukt/twitter/blob/master/LICENSE.md
 */

namespace dukt\twitter\controllers;

use Craft;
use craft\web\Controller;
use dukt\twitter\Plugin;
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
            $token = Plugin::getInstance()->getOauth()->getToken();

            if ($token) {
                $tokenExists = true;

                // Retrieve resource owner’s details
                $provider = Plugin::getInstance()->getOauth()->getOauthProvider();
                $resourceOwner = $provider->getUserDetails($token);
            }
        } catch (Exception $e) {
            Craft::error('Couldn’t retrieve twitter account: '.$e->getTraceAsString(), __METHOD__);
            $error = $e->getMessage();
        }

        return $this->renderTemplate('twitter/settings', [
            'error' => $error ?? null,
            'tokenExists' => $tokenExists,
            'resourceOwner' => $resourceOwner,
            'javascriptOrigin' => Plugin::getInstance()->getOauth()->getJavascriptOrigin(),
            'redirectUri' => Plugin::getInstance()->getOauth()->getRedirectUri(),
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
            'javascriptOrigin' => Plugin::getInstance()->getOauth()->getJavascriptOrigin(),
            'redirectUri' => Plugin::getInstance()->getOauth()->getRedirectUri(),
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
