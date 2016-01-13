<?php
/**
 * @link      https://dukt.net/craft/twitter/
 * @copyright Copyright (c) 2016, Dukt
 * @license   https://dukt.net/craft/twitter/docs/license
 */

namespace Craft;

class Twitter_CacheService extends BaseApplicationComponent
{
    public function get($id)
    {
        if(craft()->config->get('enableCache', 'twitter') == true)
        {
            $cacheKey = $this->getCacheKey($id);

            return craft()->cache->get($cacheKey);
        }
    }

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
                $expire = craft()->config->get('cacheDuration', 'twitter');
                $expire = TwitterHelper::formatDuration($expire);
            }

            return craft()->cache->set($cacheKey, $value, $expire, $dependency);
        }
    }

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
