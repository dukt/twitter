<?php
/**
 * @link      https://dukt.net/craft/twitter/
 * @copyright Copyright (c) 2017, Dukt
 * @license   https://dukt.net/craft/twitter/docs/license
 */

namespace Craft;

class Twitter_CacheService extends BaseApplicationComponent
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
        if(craft()->config->get('enableCache', 'twitter') == true)
        {
            $cacheKey = $this->getCacheKey($id);

            return craft()->cache->get($cacheKey);
        }
    }

	/**
	 * Set cache
	 *
	 * @param      $id
	 * @param      $value
	 * @param null $expire
	 * @param null $dependency
	 * @param null $enableCache
	 *
	 * @return mixed
	 */
	public function set($id, $value, $expire = null, $dependency = null, $enableCache = null)
    {
        if(is_null($enableCache))
        {
            $enableCache = craft()->config->get('enableCache', 'twitter');
        }

        if($enableCache)
        {
            $cacheKey = $this->getCacheKey($id);

            if(!$expire)
            {
                $duration = craft()->config->get('cacheDuration', 'twitter');
                $expire = TwitterHelper::durationToSeconds($duration);
            }

            return craft()->cache->set($cacheKey, $value, $expire, $dependency);
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

        $cacheKey = 'twitter.'.$hash;

        return $cacheKey;
    }
}
