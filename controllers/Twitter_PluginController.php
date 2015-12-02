<?php
/**
 * @link      https://dukt.net/craft/twitter/
 * @copyright Copyright (c) 2015, Dukt
 * @license   https://dukt.net/craft/twitter/docs/license
 */

namespace Craft;

/**
 * Twitter Plugin controller
 */
class Twitter_PluginController extends BaseController
{
    // Properties
    // =========================================================================

    /**
     * @var string
     */
    private $pluginHandle = 'twitter';

    /**
     * @var object
     */
    private $pluginService;

    // Public Methods
    // =========================================================================

    /**
     * Constructor
     *
     * @return null
     */
    public function __construct()
    {
        $this->pluginService = craft()->{$this->pluginHandle.'_plugin'};
    }

    /**
     * Dependencies
     *
     * @return null
     */
    public function actionInstall()
    {
        $plugin = craft()->plugins->getPlugin('twitter');
        $pluginDependencies = $plugin->getPluginDependencies();

        if (count($pluginDependencies) > 0)
        {
            $this->renderTemplate('twitter/_install/dependencies', ['pluginDependencies' => $pluginDependencies]);
        }
        else
        {
            $this->redirect('twitter/settings');
        }
    }
}