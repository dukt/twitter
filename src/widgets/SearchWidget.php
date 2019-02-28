<?php
/**
 * @link      https://dukt.net/twitter/
 * @copyright Copyright (c) 2019, Dukt
 * @license   https://github.com/dukt/twitter/blob/master/LICENSE.md
 */

namespace dukt\twitter\widgets;

use Craft;
use craft\base\Widget;
use craft\helpers\UrlHelper;
use dukt\twitter\models\Tweet;
use dukt\twitter\Plugin;
use dukt\twitter\web\assets\searchwidget\SearchWidgetAsset;
use GuzzleHttp\Exception\ClientException;

/**
 * Search Widget
 *
 * @author Dukt <support@dukt.net>
 * @since  3.0
 */
class SearchWidget extends Widget
{
    // Properties
    // =========================================================================

    /**
     * @var
     */
    public $query;
    /**
     * @var
     */
    public $count;

    // Static
    // =========================================================================

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Craft::t('twitter', 'Twitter Search');
    }

    /**
     * @inheritdoc
     */
    public static function iconPath()
    {
        return Craft::getAlias('@dukt/twitter/icons/twitter.svg');
    }

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules[] = [['query', 'count'], 'required'];
        $rules[] = [['query'], 'string'];
        $rules[] = [['count'], 'integer', 'min' => 1];

        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function getTitle(): string
    {
        $settings = $this->getSettings();

        if (!empty($settings['query'])) {
            return Craft::t('twitter', 'Tweets for “{query}”', ['query' => $settings['query']]);
        }

        return Craft::t('twitter', 'Twitter Search');
    }

    /**
     * @inheritdoc
     */
    public function getBodyHtml()
    {
        $settings = $this->getSettings();

        $searchQuery = $settings['query'];
        $count = $settings['count'];

        $token = Plugin::getInstance()->getOauth()->getToken();

        if ($token) {
            if (!empty($searchQuery)) {
                try {
                    $q = $searchQuery;

                    if (Plugin::getInstance()->getSettings()->searchWidgetExtraQuery) {
                        $q .= ' '.Plugin::getInstance()->getSettings()->searchWidgetExtraQuery;
                    }

                    $response = Plugin::getInstance()->getApi()->get('search/tweets', [
                        'q' => $q,
                        'count' => $count,
                        'tweet_mode' => 'extended'
                    ]);

                    $tweets = [];

                    foreach ($response['statuses'] as $tweetData) {
                        $tweet = new Tweet();
                        Plugin::getInstance()->getApi()->populateTweetFromData($tweet, $tweetData);
                        array_push($tweets, $tweet);
                    }

                    $variables['tweets'] = $tweets;

                    Craft::$app->getView()->registerAssetBundle(SearchWidgetAsset::class);
                    Craft::$app->getView()->registerJs("new Craft.Twitter_SearchWidget('".$this->id."');");

                    return Craft::$app->getView()->renderTemplate('twitter/_components/widgets/Search/body', $variables);
                } catch (ClientException $e) {
                    $errorMsg = $e->getMessage();
                    $data = Json::decodeIfJson($e->getResponse()->getBody()->getContents());

                    if (isset($data['errors'][0]['message'])) {
                        $errorMsg = $data['errors'][0]['message'];
                    }

                    Craft::error('Couldn’t retrieve tweets: '.$e->getTraceAsString(), __METHOD__);

                    return Craft::$app->getView()->renderTemplate('twitter/_components/widgets/Search/_error', [
                        'errorMsg' => $errorMsg
                    ]);
                }
            } else {
                $variables['infoMsg'] = Craft::t('twitter', 'Please enter a search query in the widget’s settings.');

                return Craft::$app->getView()->renderTemplate('twitter/_components/widgets/Search/_error', $variables);
            }
        } else {
            $variables['infoMsg'] = Craft::t('twitter', 'Twitter is not configured, please check the <a href="{url}">plugin’s settings</a>.', [
                'url' => UrlHelper::url('twitter/settings')
            ]);

            return Craft::$app->getView()->renderTemplate('twitter/_components/widgets/Search/_error', $variables);
        }
    }

    /**
     * @inheritdoc
     */
    public function getSettingsHtml()
    {
        return Craft::$app->getView()->renderTemplate('twitter/_components/widgets/Search/settings', [
            'settings' => $this->getSettings()
        ]);
    }
}
