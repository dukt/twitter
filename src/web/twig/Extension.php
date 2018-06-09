<?php
/**
 * @link      https://dukt.net/craft/twitter/
 * @copyright Copyright (c) 2018, Dukt
 * @license   https://dukt.net/craft/twitter/docs/license
 */

namespace dukt\twitter\web\twig;

use Craft;
use craft\helpers\Template;
use dukt\twitter\helpers\TwitterHelper;
use dukt\twitter\Plugin;
use Twig_Extension;
use Twig_SimpleFunction;
use Twig_SimpleFilter;

/**
 * Twitter Twig Extension
 */
class Extension extends Twig_Extension
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
            new Twig_SimpleFilter('autoLinkTweet', [$this, 'autoLinkTweet']),
            new Twig_SimpleFilter('twitterTimeAgo', [$this, 'timeAgo'])
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
            new Twig_SimpleFunction('twitterGrid', [$this, 'twitterGrid']),
            new Twig_SimpleFunction('twitterTimeline', [$this, 'twitterTimeline']),
            new Twig_SimpleFunction('twitterTweet', [$this, 'twitterTweet']),
            new Twig_SimpleFunction('twitterVideo', [$this, 'twitterVideo']),
            new Twig_SimpleFunction('twitterMoment', [$this, 'twitterMoment']),
            new Twig_SimpleFunction('twitterFollowButton', [$this, 'twitterFollowButton']),
            new Twig_SimpleFunction('twitterMessageButton', [$this, 'twitterMessageButton']),
            new Twig_SimpleFunction('twitterTweetButton', [$this, 'twitterTweetButton']),
            new Twig_SimpleFunction('embedTweet', [$this, 'embedTweet']),
        ];
    }

    /**
     * Returns the HTML of a Timeline widget as grid.
     *
     * @param       $url
     * @param array $options
     *
     * @return \Twig_Markup
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \yii\base\InvalidConfigException
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
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \yii\base\InvalidConfigException
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
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \yii\base\InvalidConfigException
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
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \yii\base\InvalidConfigException
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
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \yii\base\InvalidConfigException
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
     * @throws \yii\base\InvalidConfigException
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
     * @throws \yii\base\InvalidConfigException
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
     * @throws \yii\base\InvalidConfigException
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
     * @throws \yii\base\InvalidConfigException
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