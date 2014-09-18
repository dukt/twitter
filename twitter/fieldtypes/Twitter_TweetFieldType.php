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

class Twitter_TweetFieldType extends BaseFieldType
{
	/**
	 * Returns the type of field this is.
	 *
	 * @return string
	 */
	public function getName()
	{
		return Craft::t('Tweet');
	}

	/**
	 * Returns the field's input HTML.
	 *
	 * @param string $name
	 * @param mixed  $value
	 * @return string
	 */
	public function getInputHtml($name, $value)
	{
		$id = craft()->templates->formatInputId($name);

		craft()->templates->includeCssResource('twitter/css/tweet.css');
		craft()->templates->includeJsResource('twitter/js/TweetInput.js');
		craft()->templates->includeJs('new TweetInput("'.craft()->templates->namespaceInputId($id).'");');

		$tweet = $value;

		if ($tweet && isset($tweet['id']))
		{
			$url = 'https://twitter.com/'.$tweet['user']['screen_name'].'/status/'.$tweet['id'];

			if (craft()->request->isSecureConnection())
			{
				$profileImageUrl = $tweet['user']['profile_image_url_https'];
			}
			else
			{
				$profileImageUrl = $tweet['user']['profile_image_url'];
			}

			$preview =
				'<div class="tweet-preview">' .
					'<div class="tweet-image" style="background-image: url('.$profileImageUrl.');"></div> ' .
					'<div class="tweet-user">' .
						'<span class="tweet-user-name">'.$tweet['user']['name'].'</span> ' .
						'<a class="tweet-user-screenname light" href="http://twitter.com/'.$tweet['user']['screen_name'].'" target="_blank">@'.$tweet['user']['screen_name'].'</a>' .
					'</div>' .
					'<div class="tweet-text">' .
						$tweet['text'] .
					'</div>' .
				'</div>';
		}
		else
		{
			$url = $value;
			$preview = '';
		}

		return '<div class="tweet">' .
			craft()->templates->render('_includes/forms/text', array(
				'id'    => $id,
				'name'  => $name,
				'value' => $url,
				'placeholder' => Craft::t('Enter a tweet URL or ID'),
			)) .
			'<div class="spinner hidden"></div>' .
			$preview .
			'</div>';
	}

	public function prepValueFromPost($value)
	{
		if (preg_match('/^\d+$/', $value))
		{
			$id = $value;
		}
		else if (preg_match('/\/status(es)?\/(\d+)\/?$/', $value, $matches))
		{
			$id = $matches[2];
		}

		if(isset($id))
		{
			return $id;
		}
		else
		{
			return $value;
		}
	}


	/**
	 * Preps the field value for use.
	 *
	 * @param mixed $value
	 * @return mixed
	 */
	public function prepValue($value)
	{
		if($value)
		{
			if(is_numeric($value))
			{
				$tweet = craft()->twitter->getTweetById($value, array(), true);

				if($tweet)
				{
					return $tweet;
				}
				else
				{
					return $value;
				}
			}
			elseif (is_string($value))
			{
				return $value;
			}
		}
	}
}
