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
            'twitterMoment' => new \Twig_Function_Method($this, 'twitterMoment'),
            'twitterOEmbed' => new \Twig_Function_Method($this, 'twitterOEmbed'),
            'twitterFollowButton' => new \Twig_Function_Method($this, 'twitterFollowButton'),
            'twitterMessageButton' => new \Twig_Function_Method($this, 'twitterMessageButton'),
            'twitterTweetButton' => new \Twig_Function_Method($this, 'twitterTweetButton'),
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
    public function twitterGrid($url, $options = [])
    {
        $html = craft()->twitter_publish->grid($url, $options);

        return TemplateHelper::getRaw($html);
    }

    /**
     * Twitter Moment
     *
     * @param       $url
     * @param array $options
     *
     * @return \Twig_Markup
     */
    public function twitterMoment($url, $options = [])
    {
        $html = craft()->twitter_publish->moment($url, $options);

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
	public function twitterOEmbed($url, $options = [])
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
	public function twitterTimeline($url, $options = [])
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
	public function twitterTweet($url, $options = [])
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
	public function twitterVideo($url, $options = [])
    {
        $html = craft()->twitter_publish->video($url, $options);

        return TemplateHelper::getRaw($html);
    }

    public function twitterFollowButton($username, $options = [])
    {
        $html = craft()->twitter_publish->followButton($username, $options);

        return TemplateHelper::getRaw($html);
    }

    public function twitterMessageButton($recipientId, $screenName, $text = null, $options = [])
    {
        $html = craft()->twitter_publish->messageButton($recipientId, $screenName, $text, $options);

        return TemplateHelper::getRaw($html);
    }

    public function twitterTweetButton($options = [])
    {
        $html = craft()->twitter_publish->tweetButton($options);

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
	public function autoLinkTweet($text, $options = [])
	{
		$html = craft()->twitter->autoLinkTweet($text, $options);

		return TemplateHelper::getRaw($html);
	}

    /**
     * Time Ago
     *
     * @param       $date
     *
     * @return \Twig_Markup
     */
	public function timeAgo($date)
	{
		$html = TwitterHelper::timeAgo($date);

		return TemplateHelper::getRaw($html);
	}
}