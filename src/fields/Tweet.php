<?php
/**
 * @link      https://dukt.net/twitter/
 * @copyright Copyright (c) Dukt
 * @license   https://github.com/dukt/twitter/blob/master/LICENSE.md
 */

namespace dukt\twitter\fields;

use Craft;
use craft\base\Field;
use dukt\twitter\helpers\TwitterHelper;
use dukt\twitter\models\Tweet as TweetModel;
use dukt\twitter\Plugin;
use dukt\twitter\web\assets\tweetfield\TweetFieldAsset;

/**
 * Tweet field
 *
 * @author Dukt <support@dukt.net>
 * @since  3.0
 */
class Tweet extends Field
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Craft::t('twitter', 'Tweet');
    }

    /**
     * @inheritdoc
     */
    public function getInputHtml(mixed $value, ?\craft\base\ElementInterface $element = null): string
    {
        $name = $this->handle;

        $id = Craft::$app->getView()->formatInputId($name);

        $previewHtml = '';

        if ($value) {
            $tweetId = TwitterHelper::extractTweetId($value);

            if ($tweetId) {
                $cachedTweet = $this->getCachedTweet($tweetId);

                if ($cachedTweet) {
                    $tweet = new TweetModel();
                    Plugin::getInstance()->getApi()->populateTweetFromData($tweet, $cachedTweet);

                    $previewHtml = Craft::$app->getView()->renderTemplate('twitter/_components/tweet', [
                        'tweet' => $tweet,
                    ]);
                }
            }
        }

        Craft::$app->getView()->registerAssetBundle(TweetFieldAsset::class);
        Craft::$app->getView()->registerJs('new TweetInput("' . Craft::$app->getView()->namespaceInputId($id) . '");');

        return '<div class="tweet-field">' .
            Craft::$app->getView()->renderTemplate('_includes/forms/text', [
                'id' => $id,
                'name' => $name,
                'value' => $value,
                'placeholder' => Craft::t('twitter', 'Enter a tweet URL or ID'),
            ]) .
            '<div class="spinner hidden"></div>' .
            '<div class="preview' . ($previewHtml ? '' : ' hidden') . '">' . $previewHtml . '</div>' .
            '</div>';
    }

    // Private Methods
    // =========================================================================

    /**
     * @param string|int $tweetId
     *
     * @return mixed
     */
    private function getCachedTweet($tweetId)
    {
        $uri = 'statuses/show';
        $headers = null;
        $options = [
            'query' => [
                'id' => $tweetId,
                'tweet_mode' => 'extended',
            ],
        ];

        return Plugin::getInstance()->getCache()->get([$uri, $headers, $options]);
    }
}
