<?php

namespace Craft;

class TwitterVariable
{
	public function get($url, $params = array(), $opts = array())
	{
		return craft()->twitter->get($url, $params, $opts);
	}
}