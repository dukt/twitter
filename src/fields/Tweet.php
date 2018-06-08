<?php
/**
 * @link      https://dukt.net/craft/twitter/
 * @copyright Copyright (c) 2018, Dukt
 * @license   https://dukt.net/craft/twitter/docs/license
 */

namespace dukt\twitter\fields;

use Craft;
use craft\base\Field;
use craft\helpers\StringHelper;
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
    public function getInputHtml($value, \craft\base\ElementInterface $element = null): string
    {
        $name = $this->handle;

        $id = Craft::$app->getView()->formatInputId($name);

        $previewHtml = '';

        Craft::$app->getView()->registerAssetBundle(TweetFieldAsset::class);
        Craft::$app->getView()->registerJs('new TweetInput("'.Craft::$app->getView()->namespaceInputId($id).'");');

        return '<div class="tweet-field">'.
            Craft::$app->getView()->renderTemplate('_includes/forms/text', [
                'id' => $id,
                'name' => $name,
                'value' => $value,
                'placeholder' => Craft::t('twitter', 'Enter a tweet URL or ID'),
            ]).
            '<div class="spinner hidden"></div>'.
            '<div class="preview hidden">'.$previewHtml.'</div>'.
            '</div>';
    }
}
