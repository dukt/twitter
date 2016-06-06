<?php
/**
 * @link      https://dukt.net/craft/twitter/
 * @copyright Copyright (c) 2016, Dukt
 * @license   https://dukt.net/craft/twitter/docs/license
 */

namespace Craft;

class TwitterTwigExtension extends \Twig_Extension
{
    // Public Methods
    // =========================================================================

	/**
	 * Get Name
	 *
	 * @return string
	 */
	public function getName()
    {
        return 'Twitter';
    }

	/**
	 * Get Filters
	 *
	 * @return array
	 */
	public function getFilters()
    {
        return array(
	        'autoLinkTweet' => new \Twig_Filter_Method($this, 'autoLinkTweet'),
	        'twitterTimeAgo' => new \Twig_Filter_Method($this, 'timeAgo')
        );
    }

	/**
	 * Get Functions
	 *
	 * @return array
	 */
	public function getFunctions()
    {
        return array('embedTweet' => new \Twig_Function_Method($this, 'embedTweet'));
    }

	/**
	 * Get Embed Tweet
	 *
	 * @param       $id
	 * @param array $options
	 *
	 * @return \Twig_Markup
	 */
	public function embedTweet($id, $options = array())
    {
        $html = craft()->twitter->embedTweet($id, $options);

        return TemplateHelper::getRaw($html);
    }

	/**
	 * Auto Link Tweet
	 *
	 * @param       $text
	 * @param array $options
	 *
	 * @return \Twig_Markup
	 */
	public function autoLinkTweet($text, $options = array())
	{
		$html = craft()->twitter->autoLinkTweet($text, $options);

		return TemplateHelper::getRaw($html);
	}

	public function timeAgo($date)
	{
		$html = TwitterHelper::timeAgo($date);

		return TemplateHelper::getRaw($html);
	}
}