<?php
/**
 * @link      https://dukt.net/craft/twitter/
 * @copyright Copyright (c) 2015, Dukt
 * @license   https://dukt.net/craft/twitter/docs/license
 */

namespace Craft;

class TwitterService extends BaseApplicationComponent
{
    // Properties
    // =========================================================================

    /**
     * @var Oauth_TokenModel|null
     */
    private $token;

    // Public Methods
    // =========================================================================

    /**
     * Auto Link Tweet
     */
    public function autoLinkTweet($text, $options = array())
    {
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
     * Embed Tweet
     */
    public function embedTweet($id, $params = array())
    {
        try {
            // params

            $opts = array();

            $aliases = array(
                'conversations' => 'data-conversations',
                'cards' => 'data-cards',
                'linkColor' => 'data-link-color',
                'theme' => 'data-theme'
            );

            foreach($aliases as $aliasKey => $alias)
            {
                if(!empty($params[$aliasKey]))
                {
                    $opts[$alias] = $params[$aliasKey];
                    unset($params[$alias]);
                }
            }

            $params = array_merge($opts, $params);
            $paramsString = '';

            foreach($params as $paramKey => $paramValue)
            {
                $paramsString .= ' '.$paramKey.'="'.$paramValue.'"';
            }


            // oembed

            $response = $this->get('statuses/oembed', array('id' => $id));

            if($response)
            {
                $html = $response['html'];

                $html = str_replace('<blockquote class="twitter-tweet">', '<blockquote class="twitter-tweet"'.$paramsString.'>', $html);


                return $html;
            }
        }
        catch(\Exception $e)
        {
            return false;
        }
    }

    /**
     * Returns a tweet by its ID.
     *
     * @param int $tweetId
     * @param array $params
     * @return array|null
     */
    public function getTweetById($tweetId, $params = array(), $enableCache = false)
    {
        $params = array_merge($params, array('id' => $tweetId));
        return $this->get('statuses/show', $params, array(), $enableCache);
    }

    /**
     * Returns a user by their ID.
     *
     * @param int $userId
     * @param array $params
     * @return array|null
     */
    public function getUserById($userId, $params = array())
    {
        $params = array_merge($params, array('user_id' => $userId));
        return $this->get('users/show', $params);
    }
}