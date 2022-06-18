<?php
/**
 * @link      https://dukt.net/twitter/
 * @copyright Copyright (c) Dukt
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
        /** @var Plugin $this */
        return $this->get('twitter');
    }

    /**
     * Returns the accounts service.
     *
     * @return \dukt\twitter\services\Accounts The accounts service
     */
    public function getAccounts()
    {
        /** @var Plugin $this */
        return $this->get('accounts');
    }

    /**
     * Returns the api service.
     *
     * @return \dukt\twitter\services\Api The api service
     */
    public function getApi()
    {
        /** @var Plugin $this */
        return $this->get('api');
    }

    /**
     * Returns the cache service.
     *
     * @return \dukt\twitter\services\Cache The cache service
     */
    public function getCache()
    {
        /** @var Plugin $this */
        return $this->get('cache');
    }

    /**
     * Returns the oauth service.
     *
     * @return \dukt\twitter\services\Oauth The oauth service
     */
    public function getOauth()
    {
        /** @var Plugin $this */
        return $this->get('oauth');
    }

    /**
     * Returns the publish service.
     *
     * @return \dukt\twitter\services\Publish The publish service
     */
    public function getPublish()
    {
        /** @var Plugin $this */
        return $this->get('publish');
    }

    /**
     * Gets the OAuth consumer key
     *
     * @param bool $parse
     * @return string|null
     */
    public function getConsumerKey(bool $parse = true)
    {
        $consumerKey = Plugin::$plugin->getSettings()->oauthConsumerKey;

        if (!$consumerKey) {
            return null;
        }

        return $parse ? Craft::parseEnv($consumerKey) : $consumerKey;
    }

    /**
     * Gets the OAuth consumer secret
     *
     * @param bool $parse
     * @return string|null
     */
    public function getConsumerSecret(bool $parse = true)
    {
        $consumerSecret = Plugin::$plugin->getSettings()->oauthConsumerSecret;

        if (!$consumerSecret) {
            return null;
        }

        return $parse ? Craft::parseEnv($consumerSecret) : $consumerSecret;
    }
}
