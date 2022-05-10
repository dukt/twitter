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
use Twig_Extension;
use Twig_SimpleFunction;
use Twig_SimpleFilter;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Twitter Twig Extension
 */
class Extension extends \Twig\Extension\AbstractExtension
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
            new \Twig\TwigFilter('autoLinkTweet', function ($text, array $options = []) : \Twig\Markup {
                return $this->autoLinkTweet($text, $options);
            }),
            new \Twig\TwigFilter('twitterTimeAgo', function ($date) : \Twig\Markup {
                return $this->timeAgo($date);
            })
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
            new \Twig\TwigFunction('twitterGrid', function ($url, array $options = []) : \Twig\Markup {
                return $this->twitterGrid($url, $options);
            }),
            new \Twig\TwigFunction('twitterTimeline', function ($url, array $options = []) : \Twig\Markup {
                return $this->twitterTimeline($url, $options);
            }),
            new \Twig\TwigFunction('twitterTweet', function ($url, array $options = []) : \Twig\Markup {
                return $this->twitterTweet($url, $options);
            }),
            new \Twig\TwigFunction('twitterVideo', function ($url, array $options = []) : \Twig\Markup {
                return $this->twitterVideo($url, $options);
            }),
            new \Twig\TwigFunction('twitterMoment', function ($url, array $options = []) : \Twig\Markup {
                return $this->twitterMoment($url, $options);
            }),
            new \Twig\TwigFunction('twitterFollowButton', function ($username, array $options = []) : \Twig\Markup {
                return $this->twitterFollowButton($username, $options);
            }),
            new \Twig\TwigFunction('twitterMessageButton', function ($recipientId, $screenName, $text = null, array $options = []) : \Twig\Markup {
                return $this->twitterMessageButton($recipientId, $screenName, $text, $options);
            }),
            new \Twig\TwigFunction('twitterTweetButton', function (array $options = []) : \Twig\Markup {
                return $this->twitterTweetButton($options);
            }),
            new \Twig\TwigFunction('embedTweet', [$this, 'embedTweet']),
        ];
    }

    /**
     * Returns the HTML of a Timeline widget as grid.
     *
     * @param       $url
     *
     * @return \Twig\Markup
     * @throws GuzzleException
     */
    public function twitterGrid($url, array $options = [])
    {
        $html = Plugin::getInstance()->getPublish()->grid($url, $options);

        return Template::raw($html);
    }

    /**
     * Returns the HTML of a Moment widget.
     *
     * @param       $url
     *
     * @return \Twig\Markup
     * @throws GuzzleException
     */
    public function twitterMoment($url, array $options = [])
    {
        $html = Plugin::getInstance()->getPublish()->moment($url, $options);

        return Template::raw($html);
    }

    /**
     * Returns the HTML of a Timeline widget.
     *
     * @param       $url
     *
     * @return \Twig\Markup
     * @throws GuzzleException
     */
    public function twitterTimeline($url, array $options = [])
    {
        $html = Plugin::getInstance()->getPublish()->timeline($url, $options);

        return Template::raw($html);
    }

    /**
     * Returns the HTML of a Tweet widget.
     *
     * @param       $url
     *
     * @return \Twig\Markup
     * @throws GuzzleException
     */
    public function twitterTweet($url, array $options = [])
    {
        $html = Plugin::getInstance()->getPublish()->tweet($url, $options);

        return Template::raw($html);
    }

    /**
     * Returns the HTML of a Video Tweet widget.
     *
     * @param       $url
     *
     * @return \Twig\Markup
     * @throws GuzzleException
     */
    public function twitterVideo($url, array $options = [])
    {
        $html = Plugin::getInstance()->getPublish()->video($url, $options);

        return Template::raw($html);
    }

    /**
     * Returns the HTML of a Follow Button.
     *
     * @param       $username
     *
     * @return \Twig\Markup
     */
    public function twitterFollowButton($username, array $options = [])
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
     *
     * @return \Twig\Markup
     */
    public function twitterMessageButton($recipientId, $screenName, $text = null, array $options = [])
    {
        $html = Plugin::getInstance()->getPublish()->messageButton($recipientId, $screenName, $text, $options);

        return Template::raw($html);
    }

    /**
     * Returns the HTML of a Tweet Button.
     *
     *
     * @return \Twig\Markup
     * @param mixed[] $options
     */
    public function twitterTweetButton(array $options = [])
    {
        $html = Plugin::getInstance()->getPublish()->tweetButton($options);

        return Template::raw($html);
    }

    /**
     * Auto Link Tweet
     *
     * @param       $text
     *
     * @return \Twig\Markup
     */
    public function autoLinkTweet(string $text, array $options = [])
    {
        $html = Plugin::getInstance()->getTwitter()->autoLinkTweet($text, $options);

        return Template::raw($html);
    }

    /**
     * Time Ago
     *
     * @param       $date
     *
     * @return \Twig\Markup
     */
    public function timeAgo($date)
    {
        $html = TwitterHelper::timeAgo($date);

        return Template::raw($html);
    }
}