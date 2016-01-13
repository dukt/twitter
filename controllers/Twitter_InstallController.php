<?php
/**
 * @link      https://dukt.net/craft/twitter/
 * @copyright Copyright (c) 2016, Dukt
 * @license   https://dukt.net/craft/twitter/docs/license
 */

namespace Craft;

/**
 * Twitter Plugin controller
 */
class Twitter_InstallController extends BaseController
{
    // Public Methods
    // =========================================================================

    /**
     * Dependencies
     *
     * @return null
     */
    public function actionIndex()
    {
        $plugin = craft()->plugins->getPlugin('twitter');
        $pluginDependencies = $plugin->getPluginDependencies();

        if (count($pluginDependencies) > 0)
        {
            $this->renderTemplate('twitter/_install/dependencies', ['pluginDependencies' => $pluginDependencies]);
        }
        else
        {
            $this->redirect('twitter/settings');
        }
    }
}