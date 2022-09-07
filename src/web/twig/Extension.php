<?php
/**
 * @link      https://dukt.net/twitter/
 * @copyright Copyright (c) Dukt
 * @license   https://github.com/dukt/twitter/blob/master/LICENSE.md
 */

namespace dukt\twitter\web\twig;

use craft\helpers\Template;
use dukt\twitter\helpers\TwitterHelper;
use dukt\twitter\Plugin;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Twitter Twig Extension
 */
class Extension extends AbstractExtension
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
        return [
            new TwigFilter('autoLinkTweet', [$this, 'autoLinkTweet']),
            new TwigFilter('twitterTimeAgo', [$this, 'timeAgo'])
        ];
    }

    /**
     * Get Functions
     *
     * @return array
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('twitterGrid', [$this, 'twitterGrid']),
            new TwigFunction('twitterTimeline', [$this, 'twitterTimeline']),
            new TwigFunction('twitterTweet', [$this, 'twitterTweet']),
            new TwigFunction('twitterVideo', [$this, 'twitterVideo']),
            new TwigFunction('twitterMoment', [$this, 'twitterMoment']),
            new TwigFunction('twitterFollowButton', [$this, 'twitterFollowButton']),
            new TwigFunction('twitterMessageButton', [$this, 'twitterMessageButton']),
            new TwigFunction('twitterTweetButton', [$this, 'twitterTweetButton']),
            new TwigFunction('embedTweet', [$this, 'embedTweet']),
        ];
    }

    /**
     * Returns the HTML of a Timeline widget as grid.
     *
     * @param       $url
     * @param array $options
     *
     * @return \Twig_Markup
     * @throws GuzzleException
     */
    public function twitterGrid($url, $options = [])
    {
        $html = Plugin::getInstance()->getPublish()->grid($url, $options);

        return Template::raw($html);
    }

    /**
     * Returns the HTML of a Moment widget.
     *
     * @param       $url
     * @param array $options
     *
     * @return \Twig_Markup
     * @throws GuzzleException
     */
    public function twitterMoment($url, $options = [])
    {
        $html = Plugin::getInstance()->getPublish()->moment($url, $options);

        return Template::raw($html);
    }

    /**
     * Returns the HTML of a Timeline widget.
     *
     * @param       $url
     * @param array $options
     *
     * @return \Twig_Markup
     * @throws GuzzleException
     */
    public function twitterTimeline($url, $options = [])
    {
        $html = Plugin::getInstance()->getPublish()->timeline($url, $options);

        return Template::raw($html);
    }

    /**
     * Returns the HTML of a Tweet widget.
     *
     * @param       $url
     * @param array $options
     *
     * @return \Twig_Markup
     * @throws GuzzleException
     */
    public function twitterTweet($url, $options = [])
    {
        $html = Plugin::getInstance()->getPublish()->tweet($url, $options);

        return Template::raw($html);
    }

    /**
     * Returns the HTML of a Video Tweet widget.
     *
     * @param       $url
     * @param array $options
     *
     * @return \Twig_Markup
     * @throws GuzzleException
     */
    public function twitterVideo($url, $options = [])
    {
        $html = Plugin::getInstance()->getPublish()->video($url, $options);

        return Template::raw($html);
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
        $html = Plugin::getInstance()->getPublish()->followButton($username, $options);

        return Template::raw($html);
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
        $html = Plugin::getInstance()->getPublish()->messageButton($recipientId, $screenName, $text, $options);

        return Template::raw($html);
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
        $html = Plugin::getInstance()->getPublish()->tweetButton($options);

        return Template::raw($html);
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
        $html = Plugin::getInstance()->getTwitter()->autoLinkTweet($text, $options);

        return Template::raw($html);
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

        return Template::raw($html);
    }
}