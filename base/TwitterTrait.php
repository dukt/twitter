<?php
/**
 * @link      https://dukt.net/craft/twitter/
 * @copyright Copyright (c) 2017, Dukt
 * @license   https://dukt.net/craft/twitter/docs/license
 */
 
namespace Craft;

trait TwitterTrait
{
    // Public Methods
    // =========================================================================

    /**
     * Checks dependencies and redirects to install if one or more are missing
     */
    public function requireDependencies()
    {
        if(!$this->checkDependencies())
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
     * Checks dependencies
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
     */
    public function getMissingDependencies()
    {
        return $this->getDependencies(true);
    }

    // Private Methods
    // =========================================================================
    
    /**
     * Get Dependencies
     */
    private function getDependencies($missingOnly = false)
    {
        $plugin = craft()->plugins->getPlugin('twitter');
        
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
     * Get Dependency
     */
    private function getDependency($dependency)
    {
        $isMissing = true;
        $isInstalled = true;

        $plugin = craft()->plugins->getPlugin($dependency['handle'], false);

        if($plugin)
        {
            $currentVersion = $plugin->version;


            // requires update ?

            if(version_compare($currentVersion, $dependency['version']) >= 0)
            {
                // no (requirements OK)

                if($plugin->isInstalled && $plugin->isEnabled)
                {
                    $isMissing = false;
                }
            }
            else
            {
                // yes (requirement not OK)
            }
        }
        else
        {
            // not installed
        }

        $dependency['isMissing'] = $isMissing;
        $dependency['plugin'] = $plugin;
        $dependency['pluginLink'] = 'https://dukt.net/craft/'.$dependency['handle'];

        return $dependency;
    }
}
