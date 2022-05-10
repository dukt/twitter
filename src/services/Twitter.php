<?php
/**
 * @link      https://dukt.net/twitter/
 * @copyright Copyright (c) Dukt
 * @license   https://github.com/dukt/twitter/blob/master/LICENSE.md
 */

namespace dukt\twitter\services;

use dukt\twitter\lib\AutoLink;
use yii\base\Component;

/**
 * Twitter Service
 *
 * @author Dukt <support@dukt.net>
 * @since  3.0
 */
class Twitter extends Component
{
    // Public Methods
    // =========================================================================

    /**
     * Returns the tweet with URLs transformed into HTML links
     *
     * @param string   $text    The tweet's text.
     * @param array $options Options to pass to AutoLink.
     *
     * @return string
     */
    public function autoLinkTweet(string $text, array $options = [])
    {
        $twitter = AutoLink::create();

        $aliases = [
            'urlClass' => 'setURLClass',
            'usernameClass' => 'setUsernameClass',
            'listClass' => 'setListClass',
            'hashtagClass' => 'setHashtagClass',
            'cashtagClass' => 'setCashtagClass',
            'noFollow' => 'setNoFollow',
            'external' => 'setExternal',
            'target' => 'setTarget',
            'noOpener' => 'setNoOpener'
        ];

        foreach ($options as $k => $v) {
            if (isset($aliases[$k])) {
                $twitter->{$aliases[$k]}($v);
            }
        }

        return $twitter->autoLink($text);
    }
}
