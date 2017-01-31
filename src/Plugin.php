<?php
/**
 * @link      https://dukt.net/craft/twitter/
 * @copyright Copyright (c) 2017, Dukt
 * @license   https://dukt.net/craft/twitter/docs/license
 */

namespace dukt\twitter;

use craft\events\RegisterUrlRulesEvent;
use craft\web\UrlManager;
use yii\base\Event;
use dukt\twitter\models\Settings;
use dukt\twitter\widgets\SearchWidget;
use craft\services\Dashboard;
use craft\events\RegisterComponentTypesEvent;


/**
 * Twitter Plugin
 */
class Plugin extends \craft\base\Plugin
{
    public $hasSettings = true;

    // Public Methods
    // =========================================================================

    public function init()
    {
        parent::init();

        $this->setComponents([
            'twitter' => \dukt\twitter\services\Twitter::class,
            'twitter_api' => \dukt\twitter\services\Api::class,
            'twitter_cache' => \dukt\twitter\services\Cache::class,
            'twitter_oauth' => \dukt\twitter\services\Oauth::class,
            'twitter_publish' => \dukt\twitter\services\Publish::class,
        ]);

        Event::on(UrlManager::class, UrlManager::EVENT_REGISTER_CP_URL_RULES, [$this, 'registerCpUrlRules']);

        Event::on(Dashboard::class, Dashboard::EVENT_REGISTER_WIDGET_TYPES, function(RegisterComponentTypesEvent $event) {
            $event->types[] = SearchWidget::class;
        });
    }

    public function registerCpUrlRules(RegisterUrlRulesEvent $event)
    {
        $rules = [
            'twitter/settings' => 'twitter/settings/index',
            'twitter/install' => 'twitter/install/index',
        ];

        $event->rules = array_merge($event->rules, $rules);
    }

    /**
     * Get required plugins.
     *
     * @return array
     */
    public function getRequiredPlugins()
    {
        return array(
            array(
                'name' => "OAuth",
                'handle' => 'oauth',
                'url' => 'https://dukt.net/craft/oauth',
                'version' => '2.0.0'
            )
        );
    }

    /**
     * Get the Settings URL.
     *
     * @return string
     */
    public function getSettingsUrl()
    {
        return 'twitter/settings';
    }

    /**
     * Get Documentation URL.
     *
     * @return string
     */
    public function getDocumentationUrl()
    {
        return 'https://dukt.net/craft/twitter/docs/';
    }

	/**
	 * Get Release Feed URL.
     *
     * @return string
	 */
	public function getReleaseFeedUrl()
	{
		return 'https://dukt.net/craft/twitter/updates.json';
	}

    /**
     * Adds support for Twitter user photo resource paths.
     *
     * @param string $path
     *
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
                    $user = craft()->twitter_api->getUserById($userId);

                    if (!$user || empty($user['profile_image_url_https']))
                    {
                        return;
                    }

                    $url = $user['profile_image_url_https'];

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
     * Adds `craft/storage/runtime/twitter/` to the list of things the Clear Caches tool can delete.
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
     * Remove all tokens related to this plugin when uninstalled.
     *
     * @return null
     */
    public function onBeforeUninstall()
    {
        if(isset(craft()->oauth))
        {
            craft()->oauth->deleteTokensByPlugin('twitter');
        }
    }

    /**
     * Adds the Twig extension for Twitter.
     *
     * @return TwitterTwigExtension
     */
    public function addTwigExtension()
    {
        Craft::import('plugins.twitter.etc.templating.twigextensions.TwitterTwigExtension');
        return new TwitterTwigExtension();
    }

    // Protected Methods
    // =========================================================================

    /**
     * Creates and returns the model used to store the plugin’s settings.
     *
     * @return \craft\base\Model|null
     */
    protected function createSettingsModel()
    {
        return new Settings();
    }

    /**
     * Returns the rendered settings HTML, which will be inserted into the content
     * block on the settings page.
     *
     * @return string The rendered settings HTML
     */
    public function getSettingsResponse()
    {
        $url = \craft\helpers\UrlHelper::cpUrl('twitter/settings');

        \Craft::$app->controller->redirect($url);

        return '';
    }

    /**
     * Defines the settings.
     *
     * @access protected
     *
     * @return array
     */
    protected function defineSettings()
    {
        return array(
            'tokenId' => array(AttributeType::Number),
        );
    }
}
