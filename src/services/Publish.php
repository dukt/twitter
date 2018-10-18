<?php
/**
 * @link      https://dukt.net/twitter/
 * @copyright Copyright (c) 2018, Dukt
 * @license   https://github.com/dukt/twitter/blob/master/LICENSE.md
 */

namespace dukt\twitter\services;

use Craft;
use dukt\twitter\Plugin;
use GuzzleHttp\Client;
use yii\base\Component;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Publish Service
 *
 * @author Dukt <support@dukt.net>
 * @since  3.0
 */
class Publish extends Component
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
     * @throws GuzzleException
     */
    public function grid($url, $options = [])
    {
        $dataAttributes = $this->getOptionsAsDataAttributes($options);

        $response = $this->oEmbed($url);

        if (!$response) {
            return null;
        }

        $html = $response['html'];
        $html = str_replace('<a class="twitter-timeline"', '<a class="twitter-grid"'.$dataAttributes, $html);

        return $html;
    }

    /**
     * Returns the HTML of a Moment widget.
     *
     * @param       $url
     * @param array $options
     *
     * @return string
     * @throws GuzzleException
     */
    public function moment($url, $options = [])
    {
        $dataAttributes = $this->getOptionsAsDataAttributes($options);

        $response = $this->oEmbed($url);

        if (!$response) {
            return null;
        }

        $html = $response['html'];
        $html = str_replace('<a class="twitter-moment"', '<a class="twitter-moment"'.$dataAttributes, $html);

        return $html;
    }

    /**
     * Returns the HTML of a Timeline widget.
     *
     * @param       $url
     * @param array $options
     *
     * @return string
     * @throws GuzzleException
     */
    public function timeline($url, $options = [])
    {
        $dataAttributes = $this->getOptionsAsDataAttributes($options);

        $response = $this->oEmbed($url);

        if (!$response) {
            return null;
        }

        $html = $response['html'];
        $html = str_replace('<a class="twitter-timeline"', '<a class="twitter-timeline"'.$dataAttributes, $html);

        return $html;
    }

    /**
     * Returns the HTML of a Tweet widget.
     *
     * @param       $url
     * @param array $options
     *
     * @return string
     * @throws GuzzleException
     */
    public function tweet($url, $options = [])
    {
        $dataAttributes = $this->getOptionsAsDataAttributes($options);

        $response = $this->oEmbed($url);

        if (!$response) {
            return null;
        }

        $html = $response['html'];
        $html = str_replace('<blockquote class="twitter-tweet">', '<blockquote class="twitter-tweet"'.$dataAttributes.'>', $html);

        return $html;
    }

    /**
     * Returns the HTML of a Video Tweet widget.
     *
     * @param       $url
     * @param array $options
     *
     * @return string
     * @throws GuzzleException
     */
    public function video($url, $options = [])
    {
        $dataAttributes = $this->getOptionsAsDataAttributes($options);

        $response = $this->oEmbed($url, ['widget_type' => 'video']);

        if (!$response) {
            return null;
        }

        $html = $response['html'];
        $html = str_replace('<blockquote class="twitter-video">', '<blockquote class="twitter-video"'.$dataAttributes.'>', $html);

        return $html;
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

        return '<a class="twitter-follow-button" href="https://twitter.com/'.$username.'"'.$dataAttributes.'>Follow @'.$username.'</a>';
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

        return '<a class="twitter-dm-button" href="https://twitter.com/messages/compose?recipient_id='.$recipientId.'&text='.rawurlencode($text).'"'.$dataAttributes.'>Message @'.$screenName.'</a>';
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

        return '<a class="twitter-share-button" href="https://twitter.com/share"'.$dataAttributes.'>Tweet</a>';
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

        $aliases = [
            'linkColor' => 'link-color',
            'tweetLimit' => 'tweet-limit',
            'showReplies' => 'show-replies',
            'borderColor' => 'border-color',
            'ariaPolite' => 'aria-polite',
            'screenName' => 'screen-name',
            'showScreenName' => 'show-screen-name',
            'showCount' => 'show-count',
        ];

        foreach ($aliases as $key => $alias) {
            if (!empty($options[$key])) {
                $options[$alias] = $options[$key];
                unset($options[$key]);
            }
        }


        // HTML Attributes

        $dataAttributes = '';

        foreach ($options as $key => $value) {
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
     * @param array $query
     *
     * @return array|bool|float|int|string
     * @throws \Exception
     */
    private function oEmbed($url, $query = [])
    {
        if (!isset($query['omit_script'])) {
            $query['omit_script'] = true;
        }

        $query['url'] = $url;

        $options = [
            'query' => $query
        ];

        $oembed = Plugin::getInstance()->getCache()->get(['twitter.publish.oEmbed', $url, $options]);

        if (!$oembed) {
            $client = Craft::createGuzzleClient();

            $response = $client->request('GET', 'https://publish.twitter.com/oembed', $options);

            $oembed = json_decode($response->getBody(), true);

            Plugin::getInstance()->getCache()->set(['twitter.publish.oEmbed', $url, $options], $oembed);
        }

        return $oembed;
    }
}
