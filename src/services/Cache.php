<?php
/**
 * @link      https://dukt.net/twitter/
 * @copyright Copyright (c) Dukt
 * @license   https://github.com/dukt/twitter/blob/master/LICENSE.md
 */

namespace dukt\twitter\services;

use Craft;
use dukt\twitter\helpers\TwitterHelper;
use dukt\twitter\Plugin;
use yii\base\Component;
use yii\caching\Dependency;

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
     * @param array $id
     *
     * @return mixed
     */
    public function get(array $id)
    {
        if (Plugin::getInstance()->getSettings()->enableCache === true) {
            $cacheKey = $this->getCacheKey($id);

            return Craft::$app->cache->get($cacheKey);
        }
    }

    /**
     * Set cache
     *
     * @param array $id
     * @param mixed $value
     * @param int $expire
     * @param Dependency $dependency
     *
     * @return mixed
     * @throws \Exception
     */
    public function set(array $id, $value, $expire = null, $dependency = null)
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
    private function getCacheKey(array $request): string
    {
        $dataSourceClassName = 'Twitter';

        unset($request['CRAFT_CSRF_TOKEN']);

        $request[] = $dataSourceClassName;

        $hash = md5(serialize($request));

        return substr('twitter' . $hash, 0, 32);
    }
}
