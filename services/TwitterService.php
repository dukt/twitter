<?php
/**
 * @link      https://dukt.net/craft/twitter/
 * @copyright Copyright (c) 2017, Dukt
 * @license   https://dukt.net/craft/twitter/docs/license
 */

namespace Craft;

require_once(CRAFT_PLUGINS_PATH.'twitter/vendor/autoload.php');
require_once(CRAFT_PLUGINS_PATH.'twitter/base/RequirementsTrait.php');

class TwitterService extends BaseApplicationComponent
{
	// Traits
	// =========================================================================

	use RequirementsTrait;

    // Public Methods
    // =========================================================================

    /**
     * Returns the tweet with URLs transformed into HTML links
     *
     * @param int $text      The tweet's text.
     * @param array $options Options to pass to AutoLink.
     *
     * @return string
     */
    public function autoLinkTweet($text, $options = array())
    {
	    require_once(CRAFT_PLUGINS_PATH.'twitter/src/Twitter/AutoLink.php');

	    $twitter = \Twitter\AutoLink::create();

        $aliases = array(
            'urlClass' => 'setURLClass',
            'usernameClass' => 'setUsernameClass',
            'listClass' => 'setListClass',
            'hashtagClass' => 'setHashtagClass',
            'cashtagClass' => 'setCashtagClass',
            'noFollow' => 'setNoFollow',
            'external' => 'setExternal',
            'target' => 'setTarget'
        );

        foreach($options as $k => $v)
        {
            if(isset($aliases[$k]))
            {
                $twitter->{$aliases[$k]}($v);
            }
        }

        $html = $twitter->autoLink($text);

        return $html;
    }

    /**
     * Embedded Tweet
     *
     * @param       $tweetId
     * @param array $options
     *
     * @deprecated Deprecated in 1.1. Use craft()->twitter_publish->tweet() instead.
     *
     * @return string
     */
    public function embedTweet($tweetId, $options = array())
    {
        craft()->deprecator->log('craft()->twitter->embedTweet()', 'craft()->twitter->embedTweet() has been deprecated. Use craft()->twitter_publish->tweet() instead.');

        $dataAttributes = craft()->twitter_publish->getOptionsAsDataAttributes($options);

        $response = craft()->twitter_api->get('statuses/oembed', array('id' => $tweetId));

        if($response)
        {
            $html = $response['html'];

            $html = str_replace('<blockquote class="twitter-tweet">', '<blockquote class="twitter-tweet"'.$dataAttributes.'>', $html);

            return $html;
        }
    }
}
