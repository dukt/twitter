<?php
/**
 * @link      https://dukt.net/craft/twitter/
 * @copyright Copyright (c) 2017, Dukt
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

	/**
	 * @inheritDoc IWidget::getBodyHtml()
	 *
	 * @return string|false
	 */
    public function getBodyHtml()
    {
        if(craft()->twitter->checkDependencies())
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

                        craft()->templates->includeCssResource('twitter/css/twitter.css');
                        craft()->templates->includeCssResource('twitter/css/widget.css');
                        craft()->templates->includeJsResource('twitter/js/SearchWidget.js');
                        craft()->templates->includeJs("new Craft.Twitter_SearchWidget('".$this->model->id."');");

                        return craft()->templates->render('twitter/_components/widgets/Search/body', $variables);
                    }
                    catch(\Exception $e)
                    {
                        TwitterPlugin::log("Twitter error: ".__METHOD__." ".$e->getMessage(), LogLevel::Error, true);

                        $variables['errorMsg'] = $e->getMessage();

                        return craft()->templates->render('twitter/_components/widgets/Search/_error', $variables);
                    }
                }
                else
                {
                    $variables['infoMsg'] = Craft::t('Please enter a search query in the <a href="{url}">widget’s settings</a>.', array(
                        'url' => UrlHelper::getUrl('dashboard/settings/'.$this->model->id)
                    ));

                    return craft()->templates->render('twitter/_components/widgets/Search/_error', $variables);
                }
            }
            else
            {
                $variables['infoMsg'] = Craft::t('Twitter is not configured, please check the <a href="{url}">plugin’s settings</a>.', array(
                    'url' => UrlHelper::getUrl('twitter/settings')
                ));

                return craft()->templates->render('twitter/_components/widgets/Search/_error', $variables);
            }
        }
        else
        {
            $variables['infoMsg'] = Craft::t('Twitter is not configured, please check the <a href="{url}">plugin’s settings</a>.', array(
                'url' => UrlHelper::getUrl('twitter/settings')
            ));

            return craft()->templates->render('twitter/_components/widgets/Search/_error', $variables);
        }
    }

	/**
	 * @inheritDoc ISavableComponentType::getSettingsHtml()
	 *
	 * @return string
	 */
    public function getSettingsHtml()
    {
        return craft()->templates->render('twitter/_components/widgets/Search/settings', array(
           'settings' => $this->getSettings()
        ));
    }

    // Protected
    // =========================================================================

	/**
	 * @inheritDoc BaseSavableComponentType::defineSettings()
	 *
	 * @return array
	 */
    protected function defineSettings()
    {
        return array(
           'query' => array(AttributeType::String),
           'count' => array(AttributeType::Number, 'default' => 10)
        );
    }
}
