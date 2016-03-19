<?php
/**
 * @link      https://dukt.net/craft/twitter/
 * @copyright Copyright (c) 2016, Dukt
 * @license   https://dukt.net/craft/twitter/docs/license
 */

namespace Craft;

use Guzzle\Http\Client;

class Twitter_PluginService extends BaseApplicationComponent
{
    // Public Methods
    // =========================================================================

    public function requireDependencies()
    {
        $plugin = craft()->plugins->getPlugin('twitter');
        $pluginDependencies = $plugin->getPluginDependencies();

        if (count($pluginDependencies) > 0)
        {
            $url = UrlHelper::getUrl('twitter/install');
            craft()->request->redirect($url);
            return false;
        }
        else
        {
            return true;
        }
    }

    /**
     * Check Dependencies
     */
    public function checkDependencies()
    {
        $plugin = craft()->plugins->getPlugin('twitter');
        $pluginDependencies = $plugin->getPluginDependencies();

        if(count($pluginDependencies) > 0)
        {
            return false;
        }

        return true;
    }
}