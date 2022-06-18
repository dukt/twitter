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
     * @param string $url
     * @param array $options
     *
     * @return \Twig\Markup
     * @throws GuzzleException
     */
    public function twitterGrid(string $url, array $options = [])
    {
        $html = Plugin::getInstance()->getPublish()->grid($url, $options);

        return Template::raw($html);
    }

    /**
     * Returns the HTML of a Moment widget.
     *
     * @param string $url
     * @param array $options
     *
     * @return \Twig\Markup
     * @throws GuzzleException
     */
    public function twitterMoment(string $url, array $options = [])
    {
        $html = Plugin::getInstance()->getPublish()->moment($url, $options);

        return Template::raw($html);
    }

    /**
     * Returns the HTML of a Timeline widget.
     *
     * @param string $url
     * @param array $options
     *
     * @return \Twig\Markup
     * @throws GuzzleException
     */
    public function twitterTimeline(string $url, array $options = [])
    {
        $html = Plugin::getInstance()->getPublish()->timeline($url, $options);

        return Template::raw($html);
    }

    /**
     * Returns the HTML of a Tweet widget.
     *
     * @param string $url
     * @param array $options
     *
     * @return \Twig\Markup
     * @throws GuzzleException
     */
    public function twitterTweet(string $url, array $options = [])
    {
        $html = Plugin::getInstance()->getPublish()->tweet($url, $options);

        return Template::raw($html);
    }

    /**
     * Returns the HTML of a Video Tweet widget.
     *
     * @param string $url
     * @param array $options
     *
     * @return \Twig\Markup
     * @throws GuzzleException
     */
    public function twitterVideo(string $url, array $options = [])
    {
        $html = Plugin::getInstance()->getPublish()->video($url, $options);

        return Template::raw($html);
    }

    /**
     * Returns the HTML of a Follow Button.
     *
     * @param string $username
     * @param array $options
     *
     * @return \Twig\Markup
     */
    public function twitterFollowButton(string $username, array $options = [])
    {
        $html = Plugin::getInstance()->getPublish()->followButton($username, $options);

        return Template::raw($html);
    }

    /**
     * Returns the HTML of a Message Button.
     *
     * @param int $recipientId
     * @param string $screenName
     * @param null $text
     * @param array $options
     *
     * @return \Twig\Markup
     */
    public function twitterMessageButton(int $recipientId, string $screenName, $text = null, array $options = [])
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
     * @param string $text
     * @param array $options
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
     * @param \DateTime $date
     *
     * @return \Twig\Markup
     */
    public function timeAgo(\DateTime $date)
    {
        $html = TwitterHelper::timeAgo($date);

        return Template::raw($html);
    }
}