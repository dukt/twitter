<?php

/**
 * Twitter plugin for Craft CMS
 *
 * @package   Twitter
 * @author    Benjamin David
 * @copyright Copyright (c) 2014, Dukt
 * @link      https://dukt.net/craft/twitter/
 * @license   https://dukt.net/craft/twitter/docs/license
 */

namespace Craft;

class TwitterVariable
{
	public function get($uri, $params = array(), $headers = array(), $enableCache = true, $cacheExpire = 0)
	{
		return craft()->twitter->get($uri, $params, $headers, $enableCache, $cacheExpire);
	}

	public function getTweetById($tweetId, $params = array())
	{
		return craft()->twitter->getTweetById($tweetId, $params);
	}

	public function getUserById($userId, $params = array())
	{
		return craft()->twitter->getUserById($userId, $params);
	}

	public function getUserImageUrl($userId, $size = 48)
	{
		return UrlHelper::getResourceUrl('twitteruserimages/'.$userId.'/'.$size);
	}
}