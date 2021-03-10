<?php
/**
 * @link      https://dukt.net/twitter/
 * @copyright Copyright (c) 2021, Dukt
 * @license   https://github.com/dukt/twitter/blob/master/LICENSE.md
 */

namespace dukt\twitter\services;

use Craft;
use yii\base\Component;
use dukt\twitter\helpers\TwitterHelper;
use dukt\twitter\Plugin;

/**
 * Cache Service
 *
 * @author Dukt <support@dukt.net>
 * @since  3.0
 */
class Cache extends Component
{
    // Public Methods
    // =========================================================================

    /**
     * Get cache
     *
     * @param $id
     *
     * @return mixed
     */
    public function get($id)
    {
        if (Plugin::getInstance()->getSettings()->enableCache === true) {
            $cacheKey = $this->getCacheKey($id);

            return Craft::$app->cache->get($cacheKey);
        }
    }

    /**
     * Set cache
     *
     * @param      $id
     * @param      $value
     * @param null $expire
     * @param null $dependency
     *
     * @return mixed
     * @throws \Exception
     */
    public function set($id, $value, $expire = null, $dependency = null)
    {
        if (Plugin::getInstance()->getSettings()->enableCache === true) {
            $cacheKey = $this->getCacheKey($id);

            if (!$expire) {
                $duration = Plugin::getInstance()->getSettings()->cacheDuration;
                $expire = TwitterHelper::durationToSeconds($duration);
            }

            return Craft::$app->cache->set($cacheKey, $value, $expire, $dependency);
        }
    }

    // Private Methods
    // =========================================================================

    /**
     * Get cache key
     *
     * @param array $request
     *
     * @return string
     */
    private function getCacheKey(array $request)
    {
        $dataSourceClassName = 'Twitter';

        unset($request['CRAFT_CSRF_TOKEN']);

        $request[] = $dataSourceClassName;

        $hash = md5(serialize($request));

        return substr('twitter'.$hash, 0, 32);
    }
}
