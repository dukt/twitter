<?php
/**
 * @link      https://dukt.net/craft/twitter/
 * @copyright Copyright (c) 2018, Dukt
 * @license   https://dukt.net/craft/twitter/docs/license
 */

namespace dukt\twitter\helpers;

use Craft;
use craft\helpers\FileHelper;
use DateInterval;
use DateTime;
use dukt\twitter\Plugin;

/**
 * Twitter Helper
 *
 * @author Dukt <support@dukt.net>
 * @since  3.0
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
        if (preg_match('/^\d+$/', $urlOrId)) {
            // If the string is a number, return it right away
            return (int)$urlOrId;
        } else if (preg_match('/\/status(es)?\/(\d+)\/?$/', $urlOrId, $matches)) {
            // Extract the tweet ID from the URL
            return (int)$matches[2];
        }
    }

    /**
     * Returns a user image from a twitter user ID for given size. Default size is 48.
     *
     * @param int $remoteUserId
     * @param int $size
     *
     * @return string|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \craft\errors\ImageException
     * @throws \yii\base\Exception
     */
    public static function getUserProfileImageResourceUrl($remoteUserId, $size = 48)
    {
        $baseDir = Craft::$app->getPath()->getRuntimePath().DIRECTORY_SEPARATOR.'twitter'.DIRECTORY_SEPARATOR.'userimages'.DIRECTORY_SEPARATOR.$remoteUserId;
        $originalDir = $baseDir.DIRECTORY_SEPARATOR.'original';
        $dir = $baseDir.DIRECTORY_SEPARATOR.$size;
        $file = null;

        if (is_dir($dir)) {
            $files = FileHelper::findFiles($dir);

            if (count($files) > 0) {
                $file = $files[0];
            }
        }

        if (!$file) {
            // Retrieve original image
            $originalPath = null;

            if (is_dir($originalDir)) {
                $originalFiles = FileHelper::findFiles($originalDir);

                if (count($originalFiles) > 0) {
                    $originalPath = $originalFiles[0];
                }
            }
            if (!$originalPath) {
                $user = Plugin::getInstance()->getApi()->getUserById($remoteUserId);

                $url = str_replace('_normal', '', $user['profile_image_url_https']);
                $name = pathinfo($url, PATHINFO_BASENAME);
                $originalPath = $originalDir.DIRECTORY_SEPARATOR.$name;

                FileHelper::createDirectory($originalDir);
                $client = new \GuzzleHttp\Client();
                $response = $client->request('GET', $url, [
                    'save_to' => $originalPath,
                ]);

                if ($response->getStatusCode() != 200) {
                    return null;
                }
            } else {
                $name = pathinfo($originalPath, PATHINFO_BASENAME);
            }

            // Generate the thumb
            $path = $dir.DIRECTORY_SEPARATOR.$name;
            FileHelper::createDirectory($dir);
            Craft::$app->getImages()->loadImage($originalPath, false, $size)
                ->scaleToFit($size, $size)
                ->saveAs($path);
        } else {
            $name = pathinfo($file, PATHINFO_BASENAME);
        }

        return Craft::$app->getAssetManager()->getPublishedUrl($dir, true)."/{$name}";
    }

    /**
     * Formats a duration to seconds
     *
     * @param string $duration
     *
     * @return int
     * @throws \Exception
     */
    public static function durationToSeconds($duration)
    {
        $date = new DateTime;
        $current = $date->getTimestamp();
        $date->add(new DateInterval($duration));

        return $date->getTimestamp() - $current;
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
        if (is_string($date)) {
            $date = new DateTime($date);
        }

        $now = new DateTime();

        $difference = $now->getTimestamp() - $date->getTimestamp();

        $durations = self::secondsToHumanTimeDuration($difference, true, false);

        $duration = Craft::t('twitter', '{duration} ago', ['duration' => $durations[0]]);

        return $duration;
    }

    /**
     * Seconds to human duration
     *
     * @param int  $seconds           The number of seconds
     * @param bool $showSeconds       Whether to output seconds or not
     * @param bool $implodeComponents Whether to implode components or not for return
     *
     * @return string|array
     */
    public static function secondsToHumanTimeDuration($seconds, $showSeconds = true, $implodeComponents = true)
    {
        $secondsInWeek = 604800;
        $secondsInDay = 86400;
        $secondsInHour = 3600;
        $secondsInMinute = 60;

        $weeks = floor($seconds / $secondsInWeek);
        $seconds %= $secondsInWeek;

        $days = floor($seconds / $secondsInDay);
        $seconds %= $secondsInDay;

        $hours = floor($seconds / $secondsInHour);
        $seconds %= $secondsInHour;

        if ($showSeconds) {
            $minutes = floor($seconds / $secondsInMinute);
            $seconds %= $secondsInMinute;
        } else {
            $minutes = round($seconds / $secondsInMinute);
            $seconds = 0;
        }

        $timeComponents = [];

        if ($weeks) {
            $timeComponents[] = $weeks.' '.($weeks == 1 ? Craft::t('twitter', 'week') : Craft::t('twitter', 'weeks'));
        }

        if ($days) {
            $timeComponents[] = $days.' '.($days == 1 ? Craft::t('twitter', 'day') : Craft::t('twitter', 'days'));
        }

        if ($hours) {
            $timeComponents[] = $hours.' '.($hours == 1 ? Craft::t('twitter', 'hour') : Craft::t('twitter', 'hours'));
        }

        if ($minutes || (!$showSeconds && !$weeks && !$days && !$hours)) {
            $timeComponents[] = $minutes.' '.($minutes == 1 ? Craft::t('twitter', 'minute') : Craft::t('twitter', 'minutes'));
        }

        if ($seconds || ($showSeconds && !$weeks && !$days && !$hours && !$minutes)) {
            $timeComponents[] = $seconds.' '.($seconds == 1 ? Craft::t('twitter', 'second') : Craft::t('twitter', 'seconds'));
        }

        if ($implodeComponents) {
            return implode(', ', $timeComponents);
        } else {
            return $timeComponents;
        }
    }
}
