<?php
/**
 * @link      https://dukt.net/twitter/
 * @copyright Copyright (c) 2019, Dukt
 * @license   https://github.com/dukt/twitter/blob/master/LICENSE.md
 */

namespace dukt\twitter;

use Craft;
use craft\events\RegisterCacheOptionsEvent;
use craft\events\RegisterComponentTypesEvent;
use craft\events\RegisterUrlRulesEvent;
use craft\helpers\UrlHelper;
use craft\services\Dashboard;
use craft\services\Fields;
use craft\web\twig\variables\CraftVariable;
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
    public $hasCpSettings = true;

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

        Event::on(UrlManager::class, UrlManager::EVENT_REGISTER_CP_URL_RULES, function(RegisterUrlRulesEvent $event) {
            $rules = [
                'twitter/settings' => 'twitter/settings/index',
                'twitter/settings/oauth' => 'twitter/settings/oauth',
            ];

            $event->rules = array_merge($event->rules, $rules);
        });

        Event::on(Dashboard::class, Dashboard::EVENT_REGISTER_WIDGET_TYPES, function(RegisterComponentTypesEvent $event) {
            $event->types[] = SearchWidget::class;
        });

        Event::on(Fields::class, Fields::EVENT_REGISTER_FIELD_TYPES, function(RegisterComponentTypesEvent $event) {
            $event->types[] = TweetField::class;
        });

        Event::on(ClearCaches::class, ClearCaches::EVENT_REGISTER_CACHE_OPTIONS, function(RegisterCacheOptionsEvent $event) {
            $event->options[] = [
                'key' => 'twitter-caches',
                'label' => Craft::t('twitter', 'Twitter caches'),
                'action' => Craft::$app->path->getRuntimePath().'/twitter'
            ];
        });

        Event::on(CraftVariable::class, CraftVariable::EVENT_INIT, function(Event $event) {
            /** @var CraftVariable $variable */
            $variable = $event->sender;
            $variable->set('twitter', TwitterVariable::class);
        });


        // Twig extension

        Craft::$app->view->registerTwigExtension(new Extension());
    }

    /**
     * @inheritdoc
     */
    public function getSettings()
    {
        $settings = parent::getSettings();
        $configFile = Craft::$app->getConfig()->getConfigFromFile('twitter');

        if($settings) {
            $defaultSettingsModel = new Settings();

            if(!isset($configFile['cacheDuration'])) {
                $settings->cacheDuration = $defaultSettingsModel->cacheDuration;
            }

            if(!isset($configFile['enableCache'])) {
                $settings->enableCache = $defaultSettingsModel->enableCache;
            }
            if(!isset($configFile['searchWidgetExtraQuery'])) {
                $settings->searchWidgetExtraQuery = $defaultSettingsModel->searchWidgetExtraQuery;
            }
        }

        return $settings;
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
