<?php
/**
 * @link      https://dukt.net/craft/twitter/
 * @copyright Copyright (c) 2018, Dukt
 * @license   https://dukt.net/craft/twitter/docs/license
 */

namespace dukt\twitter\web\assets\twitter;

use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

/**
 * Class TwitterAsset
 *
 * @author Dukt <support@dukt.net>
 * @since  3.0
 */
class TwitterAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        // define the path that your publishable resources live
        $this->sourcePath = __DIR__.'/dist';

        // define the dependencies
        $this->depends = [
            CpAsset::class,
        ];

        // define the relative path to CSS/JS files that should be registered with the page
        // when this asset bundle is registered
        $this->css = [
            'twitter.css',
        ];

        parent::init();
    }
}