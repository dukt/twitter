<?php
/**
 * @link      https://dukt.net/craft/twitter/
 * @copyright Copyright (c) 2016, Dukt
 * @license   https://dukt.net/craft/twitter/docs/license
 */

namespace Craft;

class TwitterPlugin extends BasePlugin
{
    // Public Methods
    // =========================================================================

    /**
     * Returns the plugin’s name
     *
     * @return string The plugin’s name.
     */
    public function getName()
    {
        return Craft::t('Twitter');
    }

    /**
     * Returns the plugin’s version
     *
     * @return string The plugin’s version.
     */
    public function getVersion()
    {
        return '1.0.30';
    }

    /**
     * Returns required plugins
     *
     * @return array Required plugins
     */
    public function getRequiredPlugins()
    {
        return array(
            array(
                'name' => "OAuth",
                'handle' => 'oauth',
                'url' => 'https://dukt.net/craft/oauth',
                'version' => '1.0.0'
            )
        );
    }

    /**
     * Returns the developer’s name
     *
     * @ return string The developer’s name
     */
    public function getDeveloper()
    {
        return 'Dukt';
    }

    /**
     * Returns the developer’s URL
     *
     * @return string The developer’s URL
     */
    public function getDeveloperUrl()
    {
        return 'https://dukt.net/';
    }

    /**
     * Get Settings URL
     */
    public function getSettingsUrl()
    {
        return 'twitter/settings';
    }

    /**
     * Get Documentation URL
     */
    public function getDocumentationUrl()
    {
        return 'https://dukt.net/craft/twitter/docs/';
    }

    /**
     * Hook Register CP Routes
     */
    public function registerCpRoutes()
    {
        return array(
            'twitter/settings' => array('action' => "twitter/settings"),
            'twitter/install' => array('action' => "twitter/install/index"),
        );
    }

    /**
     * Adds support for Twitter user photo resource paths.
     *
     * @param string $path
     * @return string|null
     */
    public function getResourcePath($path)
    {
        // Are they requesting a Twitter user image?
        if (strncmp($path, 'twitteruserimages/', 18) === 0)
        {
            $parts = array_merge(array_filter(explode('/', $path)));

            if (count($parts) != 3)
            {
                return;
            }

            $userId = $parts[1];
            $size = $parts[2];

            $imageSizes = array(
                'mini' => 24,
                'normal' => 48,
                'bigger' => 73,
            );

            if (is_numeric($size) && ($sizeKey = array_search($size, $imageSizes)) !== false)
            {
                $size = $sizeKey;
            }

            $baseUserImagePath = craft()->path->getRuntimePath().'twitter/userimages/'.$userId.'/';
            $sizedFolderPath = $baseUserImagePath.$size.'/';

            // Have we already downloaded this user’s image at this size?
            $contents = IOHelper::getFolderContents($sizedFolderPath, false);

            if ($contents)
            {
                return $contents[0];
            }
            else
            {
                // Do we have the original image?
                if (!is_numeric($size))
                {
                    if ($size == 'original' || array_key_exists($size, $imageSizes))
                    {
                        $sizeName = $size;
                    }
                    else
                    {
                        return;
                    }
                }
                else
                {
                    $sizeName = 'original';

                    foreach ($imageSizes as $sizeKey => $sizeSize)
                    {
                        if ($size <= $sizeSize)
                        {
                            $sizeName = $sizeKey;
                            break;
                        }
                    }
                }

                $originalFolderPath = $baseUserImagePath.$sizeName.'/';

                $contents = IOHelper::getFolderContents($originalFolderPath, false);

                if ($contents)
                {
                    $originalPath = $contents[0];
                }
                else
                {
                    // OK, let’s fetch it then
                    $user = craft()->twitter->getUserById($userId);

                    if (!$user || empty($user['profile_image_url']))
                    {
                        return;
                    }

                    $url = $user['profile_image_url'];

                    if ($sizeName != 'normal')
                    {
                        if ($sizeName == 'original')
                        {
                            $url = str_replace('_normal', '', $url);
                        }
                        else
                        {
                            $url = str_replace('_normal', '_'.$sizeName, $url);
                        }
                    }

                    IOHelper::ensureFolderExists($originalFolderPath);

                    $fileName = pathinfo($url, PATHINFO_BASENAME);
                    $originalPath = $originalFolderPath.$fileName;

                    $response = \Guzzle\Http\StaticClient::get($url, array(
                        'save_to' => $originalPath
                    ));

                    if (!$response->isSuccessful())
                    {
                        return;
                    }
                }

                // If they were actually requesting "mini", "normal", "bigger", or "original", we're done
                if (!is_numeric($size))
                {
                    return $originalPath;
                }

                // Resize it to the requested size
                $fileName = pathinfo($originalPath, PATHINFO_BASENAME);
                $sizedPath = $sizedFolderPath.$fileName;

                IOHelper::ensureFolderExists($sizedFolderPath);

                craft()->images->loadImage($originalPath)
                    ->scaleAndCrop($size, $size)
                    ->saveAs($sizedPath);

                return $sizedPath;
            }
        }
    }

    /**
     * Adds craft/storage/runtime/twitter/ to the list of things the Clear Caches tool can delete.
     *
     * @return array
     */
    public function registerCachePaths()
    {
        return array(
            craft()->path->getRuntimePath().'twitter/' => Craft::t('Twitter resources'),
        );
    }

    /**
     * Remove all tokens related to this plugin when uninstalled
     */
    public function onBeforeUninstall()
    {
        if(isset(craft()->oauth))
        {
            craft()->oauth->deleteTokensByPlugin('twitter');
        }
    }

    /**
     * Add Twig Extension
     */
    public function addTwigExtension()
    {
        Craft::import('plugins.twitter.twigextensions.TwitterTwigExtension');
        return new TwitterTwigExtension();
    }

    /**
     * Get Plugin Dependencies
     */
    public function getPluginDependencies($missingOnly = true)
    {
        $dependencies = array();

        $plugins = $this->getRequiredPlugins();

        foreach($plugins as $key => $plugin)
        {
            $dependency = $this->getPluginDependency($plugin);

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

    // Protected Methods
    // =========================================================================

    /**
     * Defines the settings.
     *
     * @access protected
     * @return array
     */
    protected function defineSettings()
    {
        return array(
            'tokenId' => array(AttributeType::Number),
        );
    }

    // Private Methods
    // =========================================================================

    /**
     * Get Plugin Dependency
     */
    private function getPluginDependency($dependency)
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