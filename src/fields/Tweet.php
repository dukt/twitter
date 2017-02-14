<?php
/**
 * @link      https://dukt.net/craft/twitter/
 * @copyright Copyright (c) 2017, Dukt
 * @license   https://dukt.net/craft/twitter/docs/license
 */

namespace dukt\twitter\fields;

use Craft;
use craft\base\Field;
use dukt\twitter\Plugin as Twitter;
use dukt\twitter\helpers\TwitterHelper;
use dukt\twitter\web\assets\twitter\TwitterAsset;
use craft\helpers\StringHelper;

/**
 * Twitter Tweet Field Type
 */
class Tweet extends Field
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
        return Craft::t('app', 'Tweet');
    }

    /**
     * Returns the field's input HTML.
     *
     * @param string $name
     * @param Tweet|null  $tweet
     * @return string
     */
    public function getInputHtml($value, \craft\base\ElementInterface $element = NULL): string
    {
        $name = $this->handle;
        $tweet = $this->prepValue($value);

        $id = Craft::$app->getView()->formatInputId($name);

        $previewHtml = '';

        if(Twitter::$plugin->twitter->checkDependencies())
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
            $previewHtml .= '<p class="light">'.Craft::t('app', "Twitter plugin is not configured properly. Please check {url} for more informations.", array('url' => Craft::t('app', '<a href="'.UrlHelper::getUrl('twitter/settings').'">{title}</a>', array('title' => 'Twitter plugin settings')))).'</p>';
        }

        Craft::$app->getView()->registerAssetBundle(TwitterAsset::class);
        /*Craft::$app->getView()->registerCssFile('twitter/css/twitter.css');
        Craft::$app->getView()->registerJsFile('twitter/js/TweetInput.js');*/
        Craft::$app->getView()->registerJs('new TweetInput("'.Craft::$app->getView()->namespaceInputId($id).'");');

        return '<div class="tweet-field">' .
            Craft::$app->getView()->renderTemplate('_includes/forms/text', array(
                'id'    => $id,
                'name'  => $name,
                'value' => $value,
                'placeholder' => Craft::t('app', 'Enter a tweet URL or ID'),
            )) .
            '<div class="spinner hidden"></div>' .
            $previewHtml.
        '</div>';
    }

    /**
     * Preps the field value for use.
     *
     * @param string|int|null $tweetUrlOrId
     * @return Tweet|null
     */
    public function prepValue($tweetUrlOrId)
    {
        if($tweetUrlOrId)
        {
            $tweetId = TwitterHelper::extractTweetId($tweetUrlOrId);

            if($tweetId)
            {
                $tweet = new \dukt\twitter\models\Tweet;
                $tweet->remoteId = $tweetId;

                return $tweet;
            }
        }
    }

    /**
     * @inheritDoc IFieldType::getSearchKeywords()
     *
     * @param Tweet|null $tweet
     *
     * @return string
     */
    public function getSearchKeywords($value, \craft\base\ElementInterface $element): string
    {
        $tweet = $this->prepValue($value);

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

            $keywords = StringHelper::toString($parts,' ');

            return StringHelper::encodeMb4($keywords);
        }

        return '';
    }
}
