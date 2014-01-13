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

		if ($value && is_array($value))
		{
			$url = 'https://twitter.com/'.$value['user']['screen_name'].'/status/'.$value['id'];

			if (craft()->request->isSecureConnection())
			{
				$profileImageUrl = $value['user']['profile_image_url_https'];
			}
			else
			{
				$profileImageUrl = $value['user']['profile_image_url'];
			}

			$preview =
				'<div class="tweet-preview">' .
					'<div class="tweet-image" style="background-image: url('.$profileImageUrl.');"></div> ' .
					'<div class="tweet-user">' .
						'<span class="tweet-user-name">'.$value['user']['name'].'</span> ' .
						'<a class="tweet-user-screenname light" href="http://twitter.com/'.$value['user']['screen_name'].'" target="_blank">@'.$value['user']['screen_name'].'</a>' .
					'</div>' .
					'<div class="tweet-text">' .
						$value['text'] .
					'</div>' .
				'</div>';
		}
		else
		{
			$url = '';
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

	/**
	 * Returns the input value as it should be saved to the database.
	 *
	 * @param mixed $value
	 * @return mixed
	 */
	public function prepValueFromPost($value)
	{
		if (preg_match('/^\d+$/', $value))
		{
			return $value;
		}
		else if (preg_match('/\/status\/(\d+)\/?$/', $value, $matches))
		{
			return $matches[1];
		}
		else
		{
			return null;
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
		if ($value && is_numeric($value))
		{
			return craft()->twitter->getTweetById($value);
		}
	}
}
