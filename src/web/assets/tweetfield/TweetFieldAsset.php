<?php
/**
 * @link      https://dukt.net/twitter/
 * @copyright Copyright (c) 2019, Dukt
 * @license   https://github.com/dukt/twitter/blob/master/LICENSE.md
 */

namespace dukt\twitter\web\assets\tweetfield;

use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;
use dukt\twitter\web\assets\twitter\TwitterAsset;

/**
 * Class TwitterAsset
 *
 * @author Dukt <support@dukt.net>
 * @since  3.0
 */
class TweetFieldAsset extends AssetBundle
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
            TwitterAsset::class,
        ];

        // define the relative path to CSS/JS files that should be registered with the page
        // when this asset bundle is registered
        $this->css = [
            'TweetInput.css',
        ];

        $this->js = [
            'TweetInput.js',
        ];

        parent::init();
    }
}