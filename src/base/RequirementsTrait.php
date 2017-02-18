<?php
/**
 * @link      https://dukt.net/craft/twitter/
 * @copyright Copyright (c) 2017, Dukt
 * @license   https://dukt.net/craft/twitter/docs/license
 */
 
namespace dukt\twitter\base;

use Craft;
use craft\helpers\UrlHelper;

/**
 * Requirements Trait
 *
 * @author Dukt <support@dukt.net>
 * @since  3.0
 */
trait RequirementsTrait
{
    // Public Methods
    // =========================================================================


    // Private Methods
    // =========================================================================

    /**
     * Get dependencies
     *
     * @return array
     */
    private function getDependencies($missingOnly = false)
    {
        $plugin = Craft::$app->plugins->getPlugin('twitter');
        
        $dependencies = array();

        $requiredPlugins = $plugin->getRequiredPlugins();

        foreach($requiredPlugins as $key => $requiredPlugin)
        {
            $dependency = $this->getDependency($requiredPlugin);

            if($missingOnly)
            {
                if($dependency['isMissing'])
                {
                    $dependencies[] = $dependency;
                }
            }
            else
            {
                $dependencies[] = $dependency;
            }
        }

        return $dependencies;
    }

    /**
     * Get dependency
     *
     * @return array
     */
    private function getDependency($dependency)
    {
        $isMissing = true;

        $plugin = Craft::$app->plugins->getPlugin($dependency['handle'], false);

        if($plugin)
        {
            $currentVersion = $plugin->version;

            if(version_compare($currentVersion, $dependency['version']) >= 0)
            {
                $allPluginInfo = Craft::$app->plugins->getAllPluginInfo();

                if(isset($allPluginInfo[$dependency['handle']]))
                {
                    $pluginInfos = $allPluginInfo[$dependency['handle']];

                    if($pluginInfos['isInstalled'] && $pluginInfos['isEnabled'])
                    {
                        $isMissing = false;
                    }
                }
            }
        }

        $dependency['isMissing'] = $isMissing;
        $dependency['plugin'] = $plugin;
        $dependency['pluginLink'] = 'https://dukt.net/craft/'.$dependency['handle'];

        return $dependency;
    }
}
