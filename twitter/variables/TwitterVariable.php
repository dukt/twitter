<?php

namespace Craft;

class TwitterVariable
{
	public function get($url, $params = array(), $opts = array())
	{
		return craft()->twitter->get($url, $params, $opts);
	}

	public function getTweetById($tweetId, $params = array())
	{
		return craft()->twitter->getTweetById($tweetId, $params);
	}

	public function getUserImageUrl($userId, $size = 48)
	{
		return UrlHelper::getResourceUrl('twitteruserimages/'.$userId.'/'.$size);
	}
}