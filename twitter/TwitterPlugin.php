<?php

/**
 * Craft Twitter by Dukt
 *
 * @package   Craft Twitter
 * @author    Benjamin David
 * @copyright Copyright (c) 2013, Dukt
 * @license   http://dukt.net/craft/twitter/docs#license
 * @link      http://dukt.net/craft/twitter/
 */

namespace Craft;

class TwitterPlugin extends BasePlugin
{
    /**
     * Get Name
     */
    function getName()
    {
        return Craft::t('Twitter');
    }

    // --------------------------------------------------------------------

    /**
     * Get Version
     */
    function getVersion()
    {
        return '0.9.1';
    }

    // --------------------------------------------------------------------

    /**
     * Get Developer
     */
    function getDeveloper()
    {
        return 'Dukt';
    }

    // --------------------------------------------------------------------

    /**
     * Get Developer URL
     */
    function getDeveloperUrl()
    {
        return 'http://dukt.net/';
    }

    protected function defineSettings()
    {
        return array();
    }

    public function getSettingsHtml()
    {
       return craft()->templates->render('twitter/settings', array(
           'settings' => $this->getSettings()
       ));
   }
}