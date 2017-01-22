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
     * @param Twitter_TweetModel|null  $tweet
     * @return string
     */
    public function getInputHtml($name, $tweet)
    {
        $id = craft()->templates->formatInputId($name);

        $previewHtml = '';

        if(craft()->twitter->checkDependencies())
        {
            if ($tweet && $tweet->remoteId)
            {
                try
                {
                    $previewHtml .=
                        '<div class="tweet">' .
                            '<div class="tweet-image" style="background-image: url('.$tweet->getUserProfileImageUrl(100).');"></div> ' .
                            '<div class="tweet-user">' .
                            '<span class="tweet-user-name">'.$tweet->getUserName().'</span> ' .
                            '<a class="tweet-user-screenname light" href="'.$tweet->getUrl().'" target="_blank">@'.$tweet->getUserScreenName().'</a>' .
                        '</div>' .
                        '<div class="tweet-text">'. $tweet->getText() .'</div>'.
                            '<ul class="tweet-actions light">' .
                                    '<li class="tweet-date">'.TwitterHelper::timeAgo($tweet->getCreatedAt()).'</li>' .
                                '<li><a href="'.$tweet->getUrl().'">Permalink</a></li>' .
                            '</ul>' .
                        '</div>';
                }
                catch(\Exception $e)
                {
                    $previewHtml .= '<p class="error">'.$e->getMessage().'</p>';
                }
            }
        }
        else
        {
            $previewHtml .= '<p class="light">'.Craft::t("Twitter plugin is not configured properly. Please check {url} for more informations.", array('url' => Craft::t('<a href="'.UrlHelper::getUrl('twitter/settings').'">{title}</a>', array('title' => 'Twitter plugin settings')))).'</p>';
        }

        craft()->templates->includeCssResource('twitter/css/twitter.css');
        craft()->templates->includeJsResource('twitter/js/TweetInput.js');
        craft()->templates->includeJs('new TweetInput("'.craft()->templates->namespaceInputId($id).'");');

        return '<div class="tweet-field">' .
            craft()->templates->render('_includes/forms/text', array(
                'id'    => $id,
                'name'  => $name,
                'value' => $this->element->getContent()->{$name},
                'placeholder' => Craft::t('Enter a tweet URL or ID'),
            )) .
            '<div class="spinner hidden"></div>' .
            $previewHtml.
        '</div>';
    }

    /**
     * Preps the field value for use.
     *
     * @param string|int|null $tweetUrlOrId
     * @return Twitter_TweetModel|null
     */
    public function prepValue($tweetUrlOrId)
    {
        if($tweetUrlOrId)
        {
            $tweetId = TwitterHelper::extractTweetId($tweetUrlOrId);

            if($tweetId)
            {
                $tweet = new Twitter_TweetModel;
                $tweet->remoteId = $tweetId;

                return $tweet;
            }
        }
    }

    /**
     * @inheritDoc IFieldType::getSearchKeywords()
     *
     * @param Twitter_TweetModel|null $tweet
     *
     * @return string
     */
    public function getSearchKeywords($tweet)
    {
        if($tweet)
        {
            $parts = [];

            if(!empty($tweet->getRemoteId()))
            {
                $parts[] = $tweet->getRemoteId();
            }

            if(!empty($tweet->getText()))
            {
                $parts[] = $tweet->getText();
            }

            if(!empty($tweet->getUserId()))
            {
                $parts[] = $tweet->getUserId();
            }

            if(!empty($tweet->getUserName()))
            {
                $parts[] = $tweet->getUserName();
            }

            if(!empty($tweet->getUserScreenName()))
            {
                $parts[] = $tweet->getUserScreenName();
            }

            $keywords = StringHelper::arrayToString($parts,' ');

            return StringHelper::encodeMb4($keywords);
        }
    }
}
