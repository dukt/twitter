<?php

/**
 * Twitter plugin for Craft CMS
 *
 * @package   Twitter
 * @author    Benjamin David
 * @copyright Copyright (c) 2014, Dukt
 * @link      https://dukt.net/craft/twitter/
 * @license   https://dukt.net/craft/twitter/docs/license
 */

namespace Craft;

class TwitterPlugin extends BasePlugin
{
    /**
     * Get Name
     */
    function getName()
    {
        return Craft::t('Twitter');
    }

    /**
     * Get Version
     */
    function getVersion()
    {
        return '0.9.4';
    }

    /**
     * Get Developer
     */
    function getDeveloper()
    {
        return 'Dukt';
    }

    /**
     * Get Developer URL
     */
    function getDeveloperUrl()
    {
        return 'https://dukt.net/';
    }

    /**
     * Defines the settings.
     *
     * @access protected
     * @return array
     */
    protected function defineSettings()
    {
        return array();
    }

    /**
     * Returns the component's settings HTML.
     *
     * @return string|null
     */
    public function getSettingsHtml()
    {
        if(craft()->request->getPath() == 'settings/plugins') {
            return true;
        }

        return craft()->templates->render('twitter/settings', array(
            'settings' => $this->getSettings()
        ));
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

            // Have we already downloaded this user's image at this size?
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
                    // OK, let's fetch it then
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
}