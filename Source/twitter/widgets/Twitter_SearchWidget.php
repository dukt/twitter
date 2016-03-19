<?php
/**
 * @link      https://dukt.net/craft/twitter/
 * @copyright Copyright (c) 2016, Dukt
 * @license   https://dukt.net/craft/twitter/docs/license
 */

namespace Craft;

class Twitter_SearchWidget extends BaseWidget
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritDoc IComponentType::getName()
     *
     * @return string
     */
    public function getName()
    {
        return Craft::t('Twitter Search');
    }

    /**
     * @inheritDoc IWidget::getTitle()
     *
     * @return string
     */
    public function getTitle()
    {
        $settings = $this->getSettings();

        if(!empty($settings->query))
        {
            return Craft::t("Tweets for “{query}”", array('query' => $settings->query));
        }

        return Craft::t("Twitter Search");
    }

    /**
     * @inheritDoc IWidget::getIconPath()
     *
     * @return string
     */
    public function getIconPath()
    {
        return craft()->resources->getResourcePath('twitter/images/widgets/search.svg');
    }

    public function getSettingsHtml()
    {
        return craft()->templates->render('twitter/widgets/search/settings', array(
           'settings' => $this->getSettings()
        ));
    }

    public function getBodyHtml()
    {
        $plugin = craft()->plugins->getPlugin('twitter');

        $pluginDependencies = $plugin->getPluginDependencies();

        if(count($pluginDependencies) == 0)
        {
            $settings = $this->getSettings();

            $query = $settings->query;
            $count = $settings->count;


            $provider = craft()->oauth->getProvider('twitter');

            $token = craft()->twitter_oauth->getToken();

            if($token)
            {
                if(!empty($query))
                {
                    $params = array('q' => $query, 'count' => $count);

                    try
                    {
                        $response = craft()->twitter_api->request('get', 'search/tweets', $params);

                        $tweets = $response['statuses'];

                        $variables['tweets'] = $tweets;

                        craft()->templates->includeCssResource('twitter/css/widget.css');

                        return craft()->templates->render('twitter/widgets/search', $variables);
                    }
                    catch(\Exception $e)
                    {
                        TwitterPlugin::log("Twitter error: ".__METHOD__." ".$e->getMessage(), LogLevel::Error, true);

                        $variables['errorMsg'] = $e->getMessage();

                        return craft()->templates->render('twitter/widgets/search/_error', $variables);
                    }
                }
                else
                {
                    $variables['infoMsg'] = Craft::t('Please enter a search query in the <a href="{url}">widget’s settings</a>.', array(
                        'url' => UrlHelper::getUrl('dashboard/settings/'.$this->model->id)
                    ));

                    return craft()->templates->render('twitter/widgets/search/_error', $variables);
                }
            }
            else
            {
                $variables['infoMsg'] = Craft::t('Twitter is not configured, please check the <a href="{url}">plugin’s settings</a>.', array(
                    'url' => UrlHelper::getUrl('twitter/settings')
                ));

                return craft()->templates->render('twitter/widgets/search/_error', $variables);
            }
        }
        else
        {
            $variables['infoMsg'] = Craft::t('Twitter is not configured, please check the <a href="{url}">plugin’s settings</a>.', array(
                'url' => UrlHelper::getUrl('twitter/settings')
            ));

            return craft()->templates->render('twitter/widgets/search/_error', $variables);
        }
    }

    public function getColspan()
    {
        $settings = $this->getSettings();

        if(isset($settings->colspan))
        {
            if($settings->colspan > 0)
            {
                return $settings->colspan;
            }
        }

        return 1;
    }

    // Protected
    // =========================================================================

    protected function defineSettings()
    {
        return array(
           'query' => array(AttributeType::String),
           'colspan' => array(AttributeType::Number, 'default' => 2),
           'count' => array(AttributeType::Number, 'default' => 10)
        );
    }
}