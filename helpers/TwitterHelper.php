<?php
/**
 * @link      https://dukt.net/craft/twitter/
 * @copyright Copyright (c) 2015, Dukt
 * @license   https://dukt.net/craft/twitter/docs/license
 */

namespace Craft;

class TwitterHelper
{
    // Public Methods
    // =========================================================================

    /**
     * Log messages only if `duktDeveMode` is set to true
     */
    public static function log($message, $level = 'info', $force = true, $category = 'application', $plugin = null)
    {
        if(craft()->config->get('duktDevMode'))
        {
            Craft::log($message, $level, $force, $category, $plugin);
        }
    }

    /**
     * Format a duration in PHP Date Interval format (to seconds by default)
     */
    public static function formatDuration($cacheDuration, $format='%s')
    {
        $cacheDuration = new DateInterval($cacheDuration);
        $cacheDurationSeconds = $cacheDuration->format('%s');

        return $cacheDurationSeconds;
    }

    /**
     * Format Time in HH:MM:SS from seconds
     *
     * @param int $seconds
     */
    public static function formatTime($seconds)
    {
        return gmdate("H:i:s", $seconds);
    }
}
