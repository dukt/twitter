<?php
/**
 * @link      https://dukt.net/craft/twitter/
 * @copyright Copyright (c) 2017, Dukt
 * @license   https://dukt.net/craft/twitter/docs/license
 */

namespace Craft;

use Guzzle\Http\Client;

class Twitter_PublishService extends BaseApplicationComponent
{
    // Public Methods
    // =========================================================================

    /**
     * Returns the HTML of a Timeline widget as grid.
     *
     * @param       $url
     * @param array $options
     *
     * @return string
     */
    public function grid($url, $options = [])
    {
        $dataAttributes = $this->getOptionsAsDataAttributes($options);

        $response = $this->oEmbed($url);

        if($response)
        {
            $html = $response['html'];
            $html = str_replace('<a class="twitter-timeline"', '<a class="twitter-grid"'.$dataAttributes, $html);

            return $html;
        }
    }

    /**
     * Returns the HTML of a Moment widget.
     *
     * @param       $url
     * @param array $options
     *
     * @return string
     */
    public function moment($url, $options = [])
    {
        $dataAttributes = $this->getOptionsAsDataAttributes($options);

        $response = $this->oEmbed($url);

        if($response)
        {
            $html = $response['html'];
            $html = str_replace('<a class="twitter-moment"', '<a class="twitter-moment"'.$dataAttributes, $html);

            return $html;
        }
    }

    /**
     * Returns the HTML of a Timeline widget.
     *
     * @param       $url
     * @param array $options
     *
     * @return string
     */
    public function timeline($url, $options = [])
    {
        $dataAttributes = $this->getOptionsAsDataAttributes($options);

        $response = $this->oEmbed($url);

        if($response)
        {
            $html = $response['html'];
            $html = str_replace('<a class="twitter-timeline"', '<a class="twitter-timeline"'.$dataAttributes, $html);

            return $html;
        }
    }

    /**
     * Returns the HTML of a Tweet widget.
     *
     * @param       $url
     * @param array $options
     *
     * @return string
     */
    public function tweet($url, $options = [])
    {
        $dataAttributes = $this->getOptionsAsDataAttributes($options);

        $response = $this->oEmbed($url);

        if($response)
        {
            $html = $response['html'];
            $html = str_replace('<blockquote class="twitter-tweet">', '<blockquote class="twitter-tweet"'.$dataAttributes.'>', $html);

            return $html;
        }
    }

    /**
     * Returns the HTML of a Video Tweet widget.
     *
     * @param       $url
     * @param array $options
     *
     * @return string
     */
    public function video($url, $options = array())
    {
        $dataAttributes = $this->getOptionsAsDataAttributes($options);

        $response = $this->oEmbed($url, ['widget_type' => 'video']);

        if($response)
        {
            $html = $response['html'];
            $html = str_replace('<blockquote class="twitter-video">', '<blockquote class="twitter-video"'.$dataAttributes.'>', $html);

            return $html;
        }
    }

    /**
     * Returns the HTML of a Follow Button.
     *
     * @param       $username
     * @param array $options
     *
     * @return string
     */
    public function followButton($username, $options = [])
    {
        $dataAttributes = $this->getOptionsAsDataAttributes($options);

        $html = '<a class="twitter-follow-button" href="https://twitter.com/'.$username.'"'.$dataAttributes.'>Follow @'.$username.'</a>';

        return $html;
    }

    /**
     * Returns the HTML of a Message Button.
     *
     * @param       $recipientId
     * @param       $screenName
     * @param null  $text
     * @param array $options
     *
     * @return string
     */
    public function messageButton($recipientId, $screenName, $text = null, $options = [])
    {
        $options['screenName'] = $screenName;

        $dataAttributes = $this->getOptionsAsDataAttributes($options);

        $html = '<a class="twitter-dm-button" href="https://twitter.com/messages/compose?recipient_id='.$recipientId.'&text='.rawurlencode($text).'"'.$dataAttributes.'>Message @'.$screenName.'</a>';

        return $html;
    }

    /**
     * Returns the HTML of a Tweet Button.
     *
     * @param array $options
     *
     * @return string
     */
    public function tweetButton($options = [])
    {
        $dataAttributes = $this->getOptionsAsDataAttributes($options);

        $html = '<a class="twitter-share-button" href="https://twitter.com/share"'.$dataAttributes.'>Tweet</a>';

        return $html;
    }

    /**
     * Returns an array of options as HTML data attributes.
     *
     * @param array $options
     *
     * @return string
     */
    public function getOptionsAsDataAttributes($options)
    {
        // Options Aliases (camel to kebab case)

        $aliases = array(
            'linkColor' => 'link-color',
            'tweetLimit' => 'tweet-limit',
            'showReplies' => 'show-replies',
            'borderColor' => 'border-color',
            'ariaPolite' => 'aria-polite',
            'screenName' => 'screen-name',
            'showScreenName' => 'show-screen-name',
            'showCount' => 'show-count',
        );

        foreach($aliases as $key => $alias)
        {
            if(!empty($options[$key]))
            {
                $options[$alias] = $options[$key];
                unset($options[$key]);
            }
        }


        // HTML Attributes

        $dataAttributes = '';

        foreach($options as $key => $value)
        {
            $dataAttributes .= ' data-'.$key.'="'.$value.'"';
        }

        return $dataAttributes;
    }
    
    // Private Methods
    // =========================================================================

    /**
     * Returns an oEmbed object from a Twitter URL.
     *
     * @param       $url
     * @param array $options
     *
     * @return array|bool|float|int|string
     */
    private function oEmbed($url, $options = [])
    {
        if(!isset($options['omit_script']))
        {
            $options['omit_script'] = true;
        }

        $oembed = craft()->twitter_cache->get(['twitter.publish.oEmbed', $url, $options]);

        if(!$oembed)
        {
            $query = array_merge(['url' => $url], $options);

            $client = new Client();
            $response = $client->get('https://publish.twitter.com/oembed', [], ['query' => $query])->send();

            $oembed = $response->json();

            craft()->twitter_cache->set(['twitter.publish.oEmbed', $url, $options], $oembed);
        }

        return $oembed;
    }
}
