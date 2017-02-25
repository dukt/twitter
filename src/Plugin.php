<?php
/**
 * @link      https://dukt.net/craft/twitter/
 * @copyright Copyright (c) 2017, Dukt
 * @license   https://dukt.net/craft/twitter/docs/license
 */

namespace dukt\twitter;

use Craft;
use craft\events\RegisterCacheOptionsEvent;
use craft\events\RegisterComponentTypesEvent;
use craft\events\RegisterUrlRulesEvent;
use craft\events\ResolveResourcePathEvent;
use craft\helpers\FileHelper;
use craft\helpers\UrlHelper;
use craft\services\Dashboard;
use craft\services\Fields;
use craft\services\Resources;
use craft\web\UrlManager;
use craft\utilities\ClearCaches;
use dukt\twitter\base\PluginTrait;
use dukt\twitter\fields\Tweet as TweetField;
use dukt\twitter\models\Settings;
use dukt\twitter\widgets\SearchWidget;
use dukt\twitter\web\twig\Extension;
use dukt\twitter\web\twig\variables\TwitterVariable;
use yii\base\Event;

/**
 * Twitter Plugin
 *
 * @author Dukt <support@dukt.net>
 * @since  3.0
 */
class Plugin extends \craft\base\Plugin
{
    // Traits
    // =========================================================================

    use PluginTrait;

    // Properties
    // =========================================================================

    /**
     * @var bool
     */
    public $hasSettings = true;

    /**
     * @var \dukt\twitter\Plugin The plugin instance.
     */
    public static $plugin;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;


        // Components

        $this->setComponents([
            'twitter' => \dukt\twitter\services\Twitter::class,
            'api' => \dukt\twitter\services\Api::class,
            'cache' => \dukt\twitter\services\Cache::class,
            'oauth' => \dukt\twitter\services\Oauth::class,
            'publish' => \dukt\twitter\services\Publish::class,
        ]);


        // Events

        Event::on(UrlManager::class, UrlManager::EVENT_REGISTER_CP_URL_RULES, [$this, 'registerCpUrlRules']);

        Event::on(Dashboard::class, Dashboard::EVENT_REGISTER_WIDGET_TYPES, function(RegisterComponentTypesEvent $event) {
            $event->types[] = SearchWidget::class;
        });

        Event::on(Fields::class, Fields::EVENT_REGISTER_FIELD_TYPES, function(RegisterComponentTypesEvent $event) {
            $event->types[] = TweetField::class;
        });

        Event::on(Resources::class, Resources::EVENT_RESOLVE_RESOURCE_PATH, function(ResolveResourcePathEvent $event) {
            if (strpos($event->uri, 'twitter/') === 0) {
                $path = $this->getResourcePath($event->uri);
                $event->path = $path;

                // Prevent other event listeners from getting invoked
                $event->handled = true;
            }
        });

        Event::on(ClearCaches::class, ClearCaches::EVENT_REGISTER_CACHE_OPTIONS, function(RegisterCacheOptionsEvent $event) {
            $event->options[] = [
                'key' => 'twitter-caches',
                'label' => Craft::t('twitter', 'Twitter caches'),
                'action' => Craft::$app->path->getStoragePath().'/twitter'
            ];
        });


        // Twig extension

        Craft::$app->view->twig->addExtension(new Extension());
    }

    /**
     * Register CP URL rules
     *
     * @param RegisterUrlRulesEvent $event
     */
    public function registerCpUrlRules(RegisterUrlRulesEvent $event)
    {
        $rules = [
            'twitter/settings' => 'twitter/settings/index',
        ];

        $event->rules = array_merge($event->rules, $rules);
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
        if (strncmp($path, 'twitter/userimages/', 18) === 0)
        {
            $parts = array_merge(array_filter(explode('/', $path)));

            if (count($parts) != 4)
            {
                return;
            }

            $userId = $parts[2];
            $size = $parts[3];

            $imageSizes = array(
                'mini' => 24,
                'normal' => 48,
                'bigger' => 73,
            );

            if (is_numeric($size) && ($sizeKey = array_search($size, $imageSizes)) !== false)
            {
                $size = $sizeKey;
            }

            $baseUserImagePath = Craft::$app->path->getRuntimePath().'/twitter/userimages/'.$userId.'/';
            $sizedFolderPath = $baseUserImagePath.$size.'/';

            if (!is_dir($sizedFolderPath)) {
                FileHelper::createDirectory($sizedFolderPath);
            }

            // Have we already downloaded this user’s image at this size?
            $files = FileHelper::findFiles($sizedFolderPath);

            if (count($files) > 0)
            {
                return $files[0];
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

                if (!is_dir($originalFolderPath)) {
                    FileHelper::createDirectory($originalFolderPath);
                }

                $files = FileHelper::findFiles($originalFolderPath);

                if (count($files) > 0)
                {
                    $originalPath = $files[0];
                }
                else
                {
                    // OK, let’s fetch it then
                    $user = self::$plugin->getApi()->getUserById($userId);

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

                    $fileName = pathinfo($url, PATHINFO_BASENAME);
                    $originalPath = $originalFolderPath.$fileName;

                    $client = new \GuzzleHttp\Client();

                    $response = $client->request('GET', $url, array(
                        'save_to' => $originalPath
                    ));

                    if (!$response->getStatusCode() != 200)
                    {
                        return;
                    }

                    return $originalPath;
                }

                // If they were actually requesting "mini", "normal", "bigger", or "original", we're done
                if (!is_numeric($size))
                {
                    return $originalPath;
                }

                // Resize it to the requested size
                $fileName = pathinfo($originalPath, PATHINFO_BASENAME);
                $sizedPath = $sizedFolderPath.$fileName;

                if (!is_dir($sizedFolderPath)) {
                    FileHelper::createDirectory($sizedFolderPath);
                }

                Craft::$app->images->loadImage($originalPath)
                    ->scaleAndCrop($size, $size)
                    ->saveAs($sizedPath);

                return $sizedPath;
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function defineTemplateComponent()
    {
        return TwitterVariable::class;
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
        $url = UrlHelper::cpUrl('twitter/settings');

        Craft::$app->controller->redirect($url);

        return '';
    }
}
