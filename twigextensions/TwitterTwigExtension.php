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
        return [
            'embedTweet' => new \Twig_Function_Method($this, 'twitterTweet'),
            'twitterGrid' => new \Twig_Function_Method($this, 'twitterGrid'),
            'twitterTimeline' => new \Twig_Function_Method($this, 'twitterTimeline'),
            'twitterTweet' => new \Twig_Function_Method($this, 'twitterTweet'),
            'twitterVideo' => new \Twig_Function_Method($this, 'twitterVideo'),
            'twitterOEmbed' => new \Twig_Function_Method($this, 'twitterOEmbed'),
        ];
    }

	/**
	 * Twitter Grid
	 *
	 * @param       $url
	 * @param array $options
	 *
	 * @return \Twig_Markup
	 */
	public function twitterGrid($url, $options = array())
    {
        $html = craft()->twitter_publish->grid($url, $options);

        return TemplateHelper::getRaw($html);
    }

	/**
	 * Twitter oEmbed
	 *
	 * @param       $url
	 * @param array $options
	 *
	 * @return \Twig_Markup
	 */
	public function twitterOEmbed($url, $options = array())
    {
        return craft()->twitter_publish->oEmbed($url, $options);
    }

	/**
	 * Twitter Timeline
	 *
	 * @param       $url
	 * @param array $options
	 *
	 * @return \Twig_Markup
	 */
	public function twitterTimeline($url, $options = array())
    {
        $html = craft()->twitter_publish->timeline($url, $options);

        return TemplateHelper::getRaw($html);
    }

	/**
	 * Twitter Tweet
	 *
	 * @param       $url
	 * @param array $options
	 *
	 * @return \Twig_Markup
	 */
	public function twitterTweet($url, $options = array())
    {
        $html = craft()->twitter_publish->tweet($url, $options);

        return TemplateHelper::getRaw($html);
    }

	/**
	 * Twitter Video
	 *
	 * @param       $url
	 * @param array $options
	 *
	 * @return \Twig_Markup
	 */
	public function twitterVideo($url, $options = array())
    {
        $html = craft()->twitter_publish->twitterVideo($url, $options);

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