<?php
/**
 * @link      https://dukt.net/craft/twitter/
 * @copyright Copyright (c) 2017, Dukt
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
            'twitterGrid' => new \Twig_Function_Method($this, 'twitterGrid'),
            'twitterTimeline' => new \Twig_Function_Method($this, 'twitterTimeline'),
            'twitterTweet' => new \Twig_Function_Method($this, 'twitterTweet'),
            'twitterVideo' => new \Twig_Function_Method($this, 'twitterVideo'),
            'twitterMoment' => new \Twig_Function_Method($this, 'twitterMoment'),
            'twitterFollowButton' => new \Twig_Function_Method($this, 'twitterFollowButton'),
            'twitterMessageButton' => new \Twig_Function_Method($this, 'twitterMessageButton'),
            'twitterTweetButton' => new \Twig_Function_Method($this, 'twitterTweetButton'),
            'embedTweet' => new \Twig_Function_Method($this, 'embedTweet'),
        ];
    }

    /**
     * Returns the HTML of a Timeline widget as grid.
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
     * Returns the HTML of a Moment widget.
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
	 * Returns the HTML of a Timeline widget.
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
	 * Returns the HTML of a Tweet widget.
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
	 * Returns the HTML of a Video Tweet widget.
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

    /**
     * Returns the HTML of a Follow Button.
     *
     * @param       $username
     * @param array $options
     *
     * @return \Twig_Markup
     */
    public function twitterFollowButton($username, $options = [])
    {
        $html = craft()->twitter_publish->followButton($username, $options);

        return TemplateHelper::getRaw($html);
    }

    /**
     * Returns the HTML of a Message Button.
     *
     * @param       $recipientId
     * @param       $screenName
     * @param null  $text
     * @param array $options
     *
     * @return \Twig_Markup
     */
    public function twitterMessageButton($recipientId, $screenName, $text = null, $options = [])
    {
        $html = craft()->twitter_publish->messageButton($recipientId, $screenName, $text, $options);

        return TemplateHelper::getRaw($html);
    }

    /**
     * Returns the HTML of a Tweet Button.
     *
     * @param array $options
     *
     * @return \Twig_Markup
     */
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

    /**
     * Embedded Tweet
     *
     * @param       $id
     * @param array $options
     *
     * @return \Twig_Markup
     */
    public function embedTweet($id, $options = [])
    {
        craft()->deprecator->log('{{ embedTweet() }}', '{{ embedTweet() }} has been deprecated. Use {{ twitterTweet() }} instead.');

        $html = craft()->twitter->embedTweet($id, $options);

        return TemplateHelper::getRaw($html);
    }
}