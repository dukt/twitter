<?php
/**
 * @link      https://dukt.net/craft/twitter/
 * @copyright Copyright (c) 2016, Dukt
 * @license   https://dukt.net/craft/twitter/docs/license
 */

namespace Craft;

class TwitterTwigExtension extends \Twig_Extension
{
    // Public Methods
    // =========================================================================

    /**
     * Get Name
     */
    public function getName()
    {
        return 'Twitter';
    }

    /**
     * Get Filters
     */
    public function getFilters()
    {
        return array('autoLinkTweet' => new \Twig_Filter_Method($this, 'autoLinkTweet'));
    }

    /**
     * Get Functions
     */
    public function getFunctions()
    {
        return array('embedTweet' => new \Twig_Function_Method($this, 'embedTweet'));
    }

    /**
     * Get Embed Tweet
     */
    public function embedTweet($id, $options = array())
    {
        $html = craft()->twitter->embedTweet($id, $options);

        return TemplateHelper::getRaw($html);
    }

    /**
     * Auto Link Tweet
     */
    public function autoLinkTweet($text, $options = array())
    {
        $html = craft()->twitter->autoLinkTweet($text, $options);

        return TemplateHelper::getRaw($html);
    }
}