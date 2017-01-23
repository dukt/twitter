<?php
/**
 * @link      https://dukt.net/craft/twitter/
 * @copyright Copyright (c) 2017, Dukt
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
        // Tweet
        $tweet = $value;

        // Input ID
        $id = craft()->templates->formatInputId($name);

        // Field raw value
        $fieldValue = $this->element->getContent()->{$name};

        $html = '';

        if(craft()->twitter->checkDependencies())
        {
            if ($tweet)
            {
                if (craft()->request->isSecureConnection())
                {
                    $profileImageUrl = $tweet['user']['profile_image_url_https'];
                }
                else
                {
                    $profileImageUrl = $tweet['user']['profile_image_url'];
                }
                
                $profileImageUrl = str_replace("_normal.", "_bigger.", $profileImageUrl);
                $permalink = 'https://twitter.com/'.$tweet['user']['screen_name'].'/status/'.$tweet['id_str'];

                $html .=
                    '<div class="tweet">' .
                        '<div class="tweet-image" style="background-image: url('.$profileImageUrl.');"></div> ' .
                        '<div class="tweet-user">' .
                        '<span class="tweet-user-name">'.$tweet['user']['name'].'</span> ' .
                        '<a class="tweet-user-screenname light" href="'.$permalink.'" target="_blank">@'.$tweet['user']['screen_name'].'</a>' .
                    '</div>' .
                    '<div class="tweet-text">'. $tweet['text'] .'</div>'.
                        '<ul class="tweet-actions light">' .
                            '<li class="tweet-date">'.TwitterHelper::timeAgo($tweet['created_at']).'</li>' .
                            '<li><a href="'.$permalink.'">Permalink</a></li>' .
                        '</ul>' .
                    '</div>';
            }
        }
        else
        {
            $html .= '<p class="light">'.Craft::t("Twitter plugin is not configured properly. Please check {url} for more informations.", array('url' => Craft::t('<a href="'.UrlHelper::getUrl('twitter/settings').'">{title}</a>', array('title' => 'Twitter plugin settings')))).'</p>';
        }

        craft()->templates->includeCssResource('twitter/css/twitter.css');
        craft()->templates->includeJsResource('twitter/js/TweetInput.js');
        craft()->templates->includeJs('new TweetInput("'.craft()->templates->namespaceInputId($id).'");');

        return '<div class="tweet-field">' .
            craft()->templates->render('_includes/forms/text', array(
                'id'    => $id,
                'name'  => $name,
                'value' => $fieldValue,
                'placeholder' => Craft::t('Enter a tweet URL or ID'),
            )) .
            '<div class="spinner hidden"></div>' .
            $html.
        '</div>';
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
            try
            {
                if(is_numeric($value))
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
                elseif (is_string($value))
                {
                    return craft()->twitter->getTweetByUrl($value);
                }
            }
            catch(\Exception $e)
            {
                // Todo: log error
                return $value;
            }
        }
    }

    public function getSearchKeywords($value)
    {
        $parts = [];

        if(isset($value['id']))
        {
            $parts[] = $value['id'];
        }

        if(isset($value['text']))
        {
            $parts[] = $value['text'];
        }

        if(isset($value['user']['id']))
        {
            $parts[] = $value['user']['id'];
        }

        if(isset($value['user']['name']))
        {
            $parts[] = $value['user']['name'];
        }

        if(isset($value['user']['screen_name']))
        {
            $parts[] = $value['user']['screen_name'];
        }

        $keywords = StringHelper::arrayToString($parts,' ');

        return StringHelper::encodeMb4($keywords);
    }
}
