<?php
/**
 * @link      https://dukt.net/twitter/
 * @copyright Copyright (c) 2019, Dukt
 * @license   https://github.com/dukt/twitter/blob/master/LICENSE.md
 */

namespace dukt\twitter\base;

use Craft;
use dukt\twitter\Plugin;

/**
 * PluginTrait implements the common methods and properties for plugin classes.
 *
 * @property \dukt\twitter\services\Twitter $twitter    The twitter service
 * @property \dukt\twitter\services\Api     $api        The api service
 * @property \dukt\twitter\services\Cache   $cache      The cache service
 * @property \dukt\twitter\services\Oauth   $oauth      The oauth service
 * @property \dukt\twitter\services\Publish $publish    The publish service
 */
trait PluginTrait
{
    /**
     * Returns the twitter service.
     *
     * @return \dukt\twitter\services\Twitter The twitter service
     */
    public function getTwitter()
    {
        /** @var Twitter $this */
        return $this->get('twitter');
    }

    /**
     * Returns the api service.
     *
     * @return \dukt\twitter\services\Api The api service
     */
    public function getApi()
    {
        /** @var Twitter $this */
        return $this->get('api');
    }

    /**
     * Returns the cache service.
     *
     * @return \dukt\twitter\services\Cache The cache service
     */
    public function getCache()
    {
        /** @var Twitter $this */
        return $this->get('cache');
    }

    /**
     * Returns the oauth service.
     *
     * @return \dukt\twitter\services\Oauth The oauth service
     */
    public function getOauth()
    {
        /** @var Twitter $this */
        return $this->get('oauth');
    }

    /**
     * Returns the publish service.
     *
     * @return \dukt\twitter\services\Publish The publish service
     */
    public function getPublish()
    {
        /** @var Twitter $this */
        return $this->get('publish');
    }

    /**
     * Returns the OAuth consumer key.
     *
     * @return string|null
     */
    public function getConsumerKey()
    {
        $consumerKey = Plugin::getInstance()->getSettings()->oauthConsumerKey;

        if ($consumerKey) {
            return $consumerKey;
        }

        $plugin = Craft::$app->getPlugins()->getPlugin('twitter');
        $settings = $plugin->getSettings();

        if (empty($settings['oauthConsumerKey'])) {
            return null;
        }

        return $settings['oauthConsumerKey'];
    }

    /**
     * Returns the OAuth consumer secret.
     *
     * @return string|null
     */
    public function getConsumerSecret()
    {
        $consumerSecret = Plugin::getInstance()->getSettings()->oauthConsumerSecret;

        if ($consumerSecret) {
            return $consumerSecret;
        }

        $plugin = Craft::$app->getPlugins()->getPlugin('twitter');
        $settings = $plugin->getSettings();

        if (!empty($settings['oauthConsumerSecret'])) {
            return null;
        }

        return $settings['oauthConsumerSecret'];
    }
}
