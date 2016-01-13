<?php
/**
 * @link      https://dukt.net/craft/twitter/
 * @copyright Copyright (c) 2016, Dukt
 * @license   https://dukt.net/craft/twitter/docs/license
 */

namespace Craft;

class Twitter_TweetFieldType extends BaseFieldType
{
    // Public Methods
    // =========================================================================

    /**
     * Returns the type of field this is.
     *
     * @return string
     */
    public function getName()
    {
        return Craft::t('Tweet');
    }

    /**
     * Returns the field's input HTML.
     *
     * @param string $name
     * @param mixed  $value
     * @return string
     */
    public function getInputHtml($name, $value)
    {
        $id = craft()->templates->formatInputId($name);

        craft()->templates->includeCssResource('twitter/css/tweet.css');
        craft()->templates->includeJsResource('twitter/js/TweetInput.js');
        craft()->templates->includeJs('new TweetInput("'.craft()->templates->namespaceInputId($id).'");');

        $tweet = $value;

        $html = "";

        if ($tweet && isset($tweet['id_str']))
        {
            $url = 'https://twitter.com/'.$tweet['user']['screen_name'].'/status/'.$tweet['id_str'];

            if (craft()->request->isSecureConnection())
            {
                $profileImageUrl = $tweet['user']['profile_image_url_https'];
            }
            else
            {
                $profileImageUrl = $tweet['user']['profile_image_url'];
            }

            if(craft()->twitter_plugin->checkDependencies())
            {
                $html .=
                    '<div class="tweet-preview">' .
                        '<div class="tweet-image" style="background-image: url('.$profileImageUrl.');"></div> ' .
                        '<div class="tweet-user">' .
                            '<span class="tweet-user-name">'.$tweet['user']['name'].'</span> ' .
                            '<a class="tweet-user-screenname light" href="http://twitter.com/'.$tweet['user']['screen_name'].'" target="_blank">@'.$tweet['user']['screen_name'].'</a>' .
                        '</div>' .
                        '<div class="tweet-text">' .
                            $tweet['text'] .
                        '</div>' .
                    '</div>';
            }
        }
        else
        {
            $url = $value;
            $preview = '';
        }

        if(!craft()->twitter_plugin->checkDependencies())
        {
            $html .= '<p class="light">'.Craft::t("Twitter plugin is not configured properly. Please check {url} for more informations.", array('url' => Craft::t('<a href="'.UrlHelper::getUrl('twitter/settings').'">{title}</a>', array('title' => 'Twitter plugin settings')))).'</p>';
        }

        return '<div class="tweet">' .
            craft()->templates->render('_includes/forms/text', array(
                'id'    => $id,
                'name'  => $name,
                'value' => $url,
                'placeholder' => Craft::t('Enter a tweet URL or ID'),
            )) .
            '<div class="spinner hidden"></div>' .
            $html.
        '</div>';
    }

    /**
     * @inheritDoc IFieldType::prepValueFromPost()
     *
     * @param mixed $data
     *
     * @return mixed
     */
    public function prepValueFromPost($value)
    {
        if (preg_match('/^\d+$/', $value))
        {
            $id = $value;
        }
        else if (preg_match('/\/status(es)?\/(\d+)\/?$/', $value, $matches))
        {
            $id = $matches[2];
        }

        if(isset($id))
        {
            return $id;
        }
        else
        {
            return $value;
        }
    }

    /**
     * Preps the field value for use.
     *
     * @param mixed $value
     * @return mixed
     */
    public function prepValue($value)
    {
        if($value)
        {
            if(is_numeric($value))
            {
                try
                {
                    $tweet = craft()->twitter->getTweetById($value, array(), true);

                    if($tweet)
                    {
                        return $tweet;
                    }
                    else
                    {
                        return $value;
                    }
                }
                catch(\Exception $e)
                {
                    return $value;
                }
            }
            elseif (is_string($value))
            {
                return $value;
            }
        }
    }
}
