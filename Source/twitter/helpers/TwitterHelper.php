<?php
/**
 * @link      https://dukt.net/craft/twitter/
 * @copyright Copyright (c) 2016, Dukt
 * @license   https://dukt.net/craft/twitter/docs/license
 */

namespace Craft;

class TwitterHelper
{
    // Public Methods
    // =========================================================================

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
