<?php

namespace Craft;

class TwitterVariable
{
	public function get($url, $opts = array())
	{
		return craft()->twitter->get($url, $opts);
	}
}