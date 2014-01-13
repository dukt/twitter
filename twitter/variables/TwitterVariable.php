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
	public function get($url, $params = array(), $opts = array(), $mode = 'get', $type = 'system')
	{
		return craft()->twitter->get($url, $params, $opts, $mode, $type);
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