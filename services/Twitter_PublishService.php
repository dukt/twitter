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

    public function oEmbed($url, $options = [])
    {
        $query  = array_merge(['url' => $url], $options);

        $client = new Client();
        $response = $client->get('https://publish.twitter.com/oembed', [], ['query' => $query])->send();

        return $response->json();
    }

    public function tweet($url, $options = [])
    {
        $htmlAttributes = $this->getOptionsAsHtmlAttributes($options);

        $response = $this->oEmbed($url, ['omit_script' => true]);

        if($response)
        {
            $html = $response['html'];
            $html = str_replace('<blockquote class="twitter-tweet">', '<blockquote class="twitter-tweet"'.$htmlAttributes.'>', $html);

            return $html;
        }
    }

    public function video($url, $options = array())
    {
        $htmlAttributes = $this->getOptionsAsHtmlAttributes($options);

        $response = $this->oEmbed($url, ['omit_script' => true, 'widget_type' => 'video']);

        if($response)
        {
            $html = $response['html'];
            $html = str_replace('<blockquote class="twitter-video">', '<blockquote class="twitter-video"'.$htmlAttributes.'>', $html);

            return $html;
        }
    }

    public function grid($url, $options = [])
    {
        $htmlAttributes = $this->getOptionsAsHtmlAttributes($options);

        $response = $this->oEmbed($url, ['omit_script' => true]);

        if($response)
        {
            $html = $response['html'];
            $html = str_replace('<a class="twitter-timeline"', '<a class="twitter-grid"'.$htmlAttributes, $html);

            return $html;
        }
    }

    public function timeline($url, $options = [])
    {
        $htmlAttributes = $this->getOptionsAsHtmlAttributes($options);

        $response = $this->oEmbed($url, ['omit_script' => true]);

        if($response)
        {
            $html = $response['html'];
            $html = str_replace('<a class="twitter-timeline"', '<a class="twitter-timeline"'.$htmlAttributes, $html);

            return $html;
        }
    }

    // Private Methods
    // =========================================================================

    private function getOptionsAsHtmlAttributes($options)
    {
        // Options Aliases (camel to kebab case)

        $aliases = array(
            'linkColor' => 'link-color',
            'tweetLimit' => 'tweet-limit',
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

        $htmlAttributes = '';

        foreach($options as $key => $value)
        {
            $htmlAttributes .= ' data-'.$key.'="'.$value.'"';
        }

        return $htmlAttributes;
    }
}
