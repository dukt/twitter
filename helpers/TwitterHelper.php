<?php
/**
 * @link      https://dukt.net/craft/twitter/
 * @copyright Copyright (c) 2017, Dukt
 * @license   https://dukt.net/craft/twitter/docs/license
 */

namespace Craft;

/**
 * Twitter Helper
 */
class TwitterHelper
{
    // Public Methods
    // =========================================================================

    /**
     * Extract the tweet ID from a tweet URL, or simply returns the ID
     *
     * @param string|int $urlOrId
     *
     * @return int
     */
    public static function extractTweetId($urlOrId)
    {
        if (preg_match('/^\d+$/', $urlOrId))
        {
            // If the string is a number, return it right away
            return (int) $urlOrId;
        }
        else if (preg_match('/\/status(es)?\/(\d+)\/?$/', $urlOrId, $matches))
        {
            // Extract the tweet ID from the URL
            return (int) $matches[2];
        }
    }

    /**
     * Returns a user image from a twitter user ID for given size. Default size is 48.
     *
     * @param int $twitterUserId
     * @param int $size
     *
     * @return string|null
     */
    public static function getUserProfileImageResourceUrl($twitterUserId, $size = 48)
    {
        return UrlHelper::getResourceUrl('twitteruserimages/'.$twitterUserId.'/'.$size);
    }

    /**
     * Format a duration in PHP Date Interval format (to seconds by default)
     *
     * @param        $duration
     * @param string $format
     *
     * @return string
     */
    public static function formatDuration($duration, $format='%s')
    {
        $duration = new DateInterval($duration);

        $durationSeconds = $duration->format('%s');

        return $durationSeconds;
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

    /**
     * Formats a date to a time ago string like “3 days ago“
     *
     * @param DateTime|string $date
     *
     * @return string
     */
    public static function timeAgo($date)
	{
		if(is_string($date))
		{
			$date = new DateTime($date);
		}

		$now = new DateTime();

		$difference = $now->getTimestamp() - $date->getTimestamp();

		$durations = self::secondsToHumanTimeDuration($difference, true, false);

		$duration = Craft::t("{duration} ago", ['duration' => $durations[0]]);

		return $duration;
	}

	/**
     * Seconds to human duration
     *
	 * @param int  $seconds     The number of seconds
	 * @param bool $showSeconds Whether to output seconds or not
	 * @param bool $implodeComponents Whether to implode components or not for return
	 *
	 * @return string|array
	 */
	public static function secondsToHumanTimeDuration($seconds, $showSeconds = true, $implodeComponents = true)
	{
		$secondsInWeek   = 604800;
		$secondsInDay    = 86400;
		$secondsInHour   = 3600;
		$secondsInMinute = 60;

		$weeks = floor($seconds / $secondsInWeek);
		$seconds = $seconds % $secondsInWeek;

		$days = floor($seconds / $secondsInDay);
		$seconds = $seconds % $secondsInDay;

		$hours = floor($seconds / $secondsInHour);
		$seconds = $seconds % $secondsInHour;

		if ($showSeconds)
		{
			$minutes = floor($seconds / $secondsInMinute);
			$seconds = $seconds % $secondsInMinute;
		}
		else
		{
			$minutes = round($seconds / $secondsInMinute);
			$seconds = 0;
		}

		$timeComponents = array();

		if ($weeks)
		{
			$timeComponents[] = $weeks.' '.($weeks == 1 ? Craft::t('week') : Craft::t('weeks'));
		}

		if ($days)
		{
			$timeComponents[] = $days.' '.($days == 1 ? Craft::t('day') : Craft::t('days'));
		}

		if ($hours)
		{
			$timeComponents[] = $hours.' '.($hours == 1 ? Craft::t('hour') : Craft::t('hours'));
		}

		if ($minutes || (!$showSeconds && !$weeks && !$days && !$hours))
		{
			$timeComponents[] = $minutes.' '.($minutes == 1 ? Craft::t('minute') : Craft::t('minutes'));
		}

		if ($seconds || ($showSeconds && !$weeks && !$days && !$hours && !$minutes))
		{
			$timeComponents[] = $seconds.' '.($seconds == 1 ? Craft::t('second') : Craft::t('seconds'));
		}

		if($implodeComponents)
		{
			return implode(', ', $timeComponents);
		}
		else
		{
			return $timeComponents;
		}
	}
}
