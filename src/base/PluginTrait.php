<?php
/**
 * @link      https://dukt.net/craft/twitter/
 * @copyright Copyright (c) 2017, Dukt
 * @license   https://dukt.net/craft/twitter/docs/license
 */

namespace dukt\twitter\base;

use dukt\twitter\Plugin as Twitter;

trait PluginTrait
{
    /**
     * Returns the twitter service.
     *
     * @return \dukt\twitter\services\Twitter The config service
     */
    public function getTwitter()
    {
        /** @var Twitter $this */
        return $this->get('twitter');
    }

    /**
     * Returns the api service.
     *
     * @return \dukt\twitter\services\Api The config service
     */
    public function getApi()
    {
        /** @var Twitter $this */
        return $this->get('api');
    }

    /**
     * Returns the cache service.
     *
     * @return \dukt\twitter\services\Cache The config service
     */
    public function getCache()
    {
        /** @var Twitter $this */
        return $this->get('cache');
    }

    /**
     * Returns the oauth service.
     *
     * @return \dukt\twitter\services\Oauth The config service
     */
    public function getOauth()
    {
        /** @var Twitter $this */
        return $this->get('oauth');
    }

    /**
     * Returns the publish service.
     *
     * @return \dukt\twitter\services\Publish The config service
     */
    public function getPublish()
    {
        /** @var Twitter $this */
        return $this->get('publish');
    }
}
