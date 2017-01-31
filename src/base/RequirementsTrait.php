<?php
/**
 * @link      https://dukt.net/craft/twitter/
 * @copyright Copyright (c) 2017, Dukt
 * @license   https://dukt.net/craft/twitter/docs/license
 */
 
namespace dukt\twitter\base;

use Craft;
use Craft\UrlHelper;

/**
 * Requirements Trait
 */
trait RequirementsTrait
{
    // Public Methods
    // =========================================================================

    /**
     * Checks dependencies and redirects to install if one or more are missing
     *
     * @return bool|null
     */
    public function requireDependencies()
    {
        if(!$this->checkDependencies())
        {
            $url = UrlHelper::getUrl('twitter/install');
            Craft::$app->request->redirect($url);
            return false;
        }
        else
        {
            return true;
        }
    }

    /**
     * Checks if dependencies are missing
     *
     * @return bool
     */
    public function checkDependencies()
    {
        $missingDependencies = $this->getMissingDependencies();

        if(count($missingDependencies) > 0)
        {
            return false;
        }

        return true;
    }

    /**
     * Get Missing Dependencies
     *
     * @return array
     */
    public function getMissingDependencies()
    {
        return $this->getDependencies(true);
    }

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
