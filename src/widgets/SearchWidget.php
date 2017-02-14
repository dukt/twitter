<?php
/**
 * @link      https://dukt.net/craft/twitter/
 * @copyright Copyright (c) 2017, Dukt
 * @license   https://dukt.net/craft/twitter/docs/license
 */

namespace dukt\twitter\widgets;

use Craft;
use craft\base\Widget;
use craft\helpers\UrlHelper;
use dukt\twitter\models\Tweet;
use dukt\twitter\Plugin as Twitter;
use dukt\twitter\web\assets\searchwidget\SearchWidgetAsset;

/**
 * Search Widget
 *
 * @author Dukt <support@dukt.net>
 * @since  3.0
 */
class SearchWidget extends Widget
{
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
        return Craft::t('app', 'Twitter Search');
    }

    /**
     * @inheritdoc
     */
    public static function iconPath()
    {
        return Craft::getAlias('@dukt/twitter/resources/images/widgets/search.svg');
    }

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function getTitle(): string
    {
        $settings = $this->getSettings();

        if(!empty($settings['query']))
        {
            return Craft::t('app', "Tweets for “{query}”", array('query' => $settings['query']));
        }

        return Craft::t('app', "Twitter Search");
    }

    /**
     * @inheritdoc
     */
    public function getBodyHtml()
    {
        if(Twitter::$plugin->twitter->checkDependencies())
        {
            $settings = $this->getSettings();

            $searchQuery = $settings['query'];
            $count = $settings['count'];

            $token = Twitter::$plugin->twitter_oauth->getToken();

            if($token)
            {
                if(!empty($searchQuery))
                {
/*                    try
                    {*/
                        $response = Twitter::$plugin->twitter_api->get('search/tweets', [
                            'q' => $searchQuery,
                            'count' => $count
                        ]);

                        $tweets = [];

                        foreach($response['statuses'] as $tweetData)
                        {
                            $tweet = new Tweet;
                            $tweet->remoteId = $tweetData['id'];
                            $tweet->data = $tweetData;
                            array_push($tweets, $tweet);
                        }

                        $variables['tweets'] = $tweets;

                        Craft::$app->getView()->registerAssetBundle(SearchWidgetAsset::class);
                        Craft::$app->getView()->registerJs("new Craft.Twitter_SearchWidget('".$this->id."');");

                        return Craft::$app->getView()->renderTemplate('twitter/_components/widgets/Search/body', $variables);
/*                    }
                    catch(\Exception $e)
                    {
                        // TwitterPlugin::log("Twitter error: ".__METHOD__." ".$e->getMessage(), LogLevel::Error, true);

                        $variables['errorMsg'] = $e->getMessage();

                        return Craft::$app->getView()->renderTemplate('twitter/_components/widgets/Search/_error', $variables);
                    }*/
                }
                else
                {
                    $variables['infoMsg'] = Craft::t('app', 'Please enter a search query in the <a href="{url}">widget’s settings</a>.', array(
                        'url' => UrlHelper::url('dashboard/settings/'.$this->id)
                    ));

                    return Craft::$app->getView()->renderTemplate('twitter/_components/widgets/Search/_error', $variables);
                }
            }
            else
            {
                $variables['infoMsg'] = Craft::t('app', 'Twitter is not configured, please check the <a href="{url}">plugin’s settings</a>.', array(
                    'url' => UrlHelper::url('twitter/settings')
                ));

                return Craft::$app->getView()->renderTemplate('twitter/_components/widgets/Search/_error', $variables);
            }
        }
        else
        {
            $variables['infoMsg'] = Craft::t('app', 'Twitter is not configured, please check the <a href="{url}">plugin’s settings</a>.', array(
                'url' => UrlHelper::url('twitter/settings')
            ));

            return Craft::$app->getView()->renderTemplate('twitter/_components/widgets/Search/_error', $variables);
        }
    }

    /**
     * @inheritdoc
     */
    public function getSettingsHtml()
    {
        return Craft::$app->getView()->renderTemplate('twitter/_components/widgets/Search/settings', array(
           'settings' => $this->getSettings()
        ));
    }
}
