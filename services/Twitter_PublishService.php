<?php
/**
 * @link      https://dukt.net/craft/twitter/
 * @copyright Copyright (c) 2016, Dukt
 * @license   https://dukt.net/craft/twitter/docs/license
 */

namespace Craft;

use Guzzle\Http\Client;

class Twitter_PublishService extends BaseApplicationComponent
{
    // Public Methods
    // =========================================================================

    public function grid($url, $options = [])
    {
        $dataAttributes = $this->getOptionsAsDataAttributes($options);

        $response = $this->oEmbed($url, ['omit_script' => true]);

        if($response)
        {
            $html = $response['html'];
            $html = str_replace('<a class="twitter-timeline"', '<a class="twitter-grid"'.$dataAttributes, $html);

            return $html;
        }
    }

    public function moment($url, $options = [])
    {
        $dataAttributes = $this->getOptionsAsDataAttributes($options);

        $response = $this->oEmbed($url, ['omit_script' => true]);

        if($response)
        {
            $html = $response['html'];
            $html = str_replace('<a class="twitter-moment"', '<a class="twitter-moment"'.$dataAttributes, $html);

            return $html;
        }
    }

    public function oEmbed($url, $options = [])
    {
        $query  = array_merge(['url' => $url], $options);

        $client = new Client();
        $response = $client->get('https://publish.twitter.com/oembed', [], ['query' => $query])->send();

        return $response->json();
    }

    public function timeline($url, $options = [])
    {
        $dataAttributes = $this->getOptionsAsDataAttributes($options);

        $response = $this->oEmbed($url, ['omit_script' => true]);

        if($response)
        {
            $html = $response['html'];
            $html = str_replace('<a class="twitter-timeline"', '<a class="twitter-timeline"'.$dataAttributes, $html);

            return $html;
        }
    }

    public function tweet($url, $options = [])
    {
        $dataAttributes = $this->getOptionsAsDataAttributes($options);

        $response = $this->oEmbed($url, ['omit_script' => true]);

        if($response)
        {
            $html = $response['html'];
            $html = str_replace('<blockquote class="twitter-tweet">', '<blockquote class="twitter-tweet"'.$dataAttributes.'>', $html);

            return $html;
        }
    }

    public function video($url, $options = array())
    {
        $dataAttributes = $this->getOptionsAsDataAttributes($options);

        $response = $this->oEmbed($url, ['omit_script' => true, 'widget_type' => 'video']);

        if($response)
        {
            $html = $response['html'];
            $html = str_replace('<blockquote class="twitter-video">', '<blockquote class="twitter-video"'.$dataAttributes.'>', $html);

            return $html;
        }
    }

    public function followButton($username, $options = [])
    {
        $dataAttributes = $this->getOptionsAsDataAttributes($options);

        $html = '<a class="twitter-follow-button" href="https://twitter.com/'.$username.'"'.$dataAttributes.'>Follow @'.$username.'</a>';

        return $html;
    }

    public function messageButton($recipientId, $screenName, $text = null, $options = [])
    {
        $options['screenName'] = $screenName;

        $dataAttributes = $this->getOptionsAsDataAttributes($options);

        $html = '<a class="twitter-dm-button" href="https://twitter.com/messages/compose?recipient_id='.$recipientId.'&text='.rawurlencode($text).'"'.$dataAttributes.'>Message @'.$screenName.'</a>';

        return $html;
    }

    public function tweetButton($options = [])
    {
        $dataAttributes = $this->getOptionsAsDataAttributes($options);

        $html = '<a class="twitter-share-button" href="https://twitter.com/share"'.$dataAttributes.'>Tweet</a>';

        return $html;
    }

    // Private Methods
    // =========================================================================

    private function getOptionsAsDataAttributes($options)
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
}
