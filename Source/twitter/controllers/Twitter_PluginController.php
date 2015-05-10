<?php

/**
 * Craft Twitter by Dukt
 *
 * @package   Craft Twitter
 * @author    Benjamin David
 * @copyright Copyright (c) 2015, Dukt
 * @license   https://dukt.net/craft/twitter/docs/license
 * @link      https://dukt.net/craft/twitter/
 */

namespace Craft;

class Twitter_PluginController extends BaseController
{
    private $pluginHandle = 'twitter';
    private $pluginService;

    // Public Methods
    // =========================================================================

    public function __construct()
    {
        $this->pluginService = craft()->{$this->pluginHandle.'_plugin'};
    }

    public function actionDownload()
    {
        Craft::log(__METHOD__, LogLevel::Info, true);

        $pluginHandle = craft()->request->getParam('plugin');


        // download plugin (includes download, unzip)

        $download = $this->pluginService->download($pluginHandle);

        if($download['success'] == true)
        {
            $this->redirect(
                UrlHelper::getActionUrl(
                    $this->pluginHandle.'/plugin/install',
                    array('plugin' => $pluginHandle, 'redirect' => craft()->request->getUrlReferrer())
                )
            );
        }
        else
        {
            // download failure

            $msg = 'Couldn’t install plugin.';

            if(isset($download['msg']))
            {
                $msg = $download['msg'];
            }

            Craft::log(__METHOD__.' : '.$msg, LogLevel::Info, true);

            craft()->userSession->setError(Craft::t($msg));
        }

        // redirect
        $this->redirect(craft()->request->getUrlReferrer());
    }

    public function actionEnable()
    {
        Craft::log(__METHOD__, LogLevel::Info, true);

        $pluginHandle = craft()->request->getParam('plugin');

        $this->pluginService->enable($pluginHandle);

        $this->redirect(craft()->request->getUrlReferrer());
    }

    public function actionInstall()
    {
        Craft::log(__METHOD__, LogLevel::Info, true);

        // pluginHandle

        $pluginHandle = craft()->request->getParam('plugin');
        $redirect = craft()->request->getParam('redirect');

        if (!$redirect)
        {
            $redirect = craft()->request->getUrlReferrer();
        }


        // install plugin

        if($this->pluginService->install($pluginHandle))
        {
            // install success
            Craft::log(__METHOD__." : ".$pluginHandle.' plugin installed.', LogLevel::Info, true);
            craft()->userSession->setNotice(Craft::t('Plugin installed.'));
        }
        else
        {
            // install failure
            Craft::log(__METHOD__." : Couldn't install ".$pluginHandle." plugin.", LogLevel::Info, true);
            craft()->userSession->setError(Craft::t("Couldn't install plugin."));
        }

        // redirect
        $this->redirect(craft()->request->getUrlReferrer());
    }
}