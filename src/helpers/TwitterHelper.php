<?php
/**
 * @link      https://dukt.net/twitter/
 * @copyright Copyright (c) Dukt
 * @license   https://github.com/dukt/twitter/blob/master/LICENSE.md
 */

namespace dukt\twitter\helpers;

use Craft;
use craft\helpers\FileHelper;
use DateInterval;
use DateTime;
use dukt\twitter\Plugin;
use GuzzleHttp\Exception\GuzzleException;

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
     * Returns a user image from a Twitter user ID for given size. Default size is 48.
     *
     * @param int $remoteUserId
     * @param int $size
     *
     * @return string|null
     * @throws GuzzleException
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
                    'sink' => $originalPath,
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
     * Extract the tweet ID from a tweet URL, or simply returns the ID.
     *
     * @param $urlOrId
     *
     * @return int|null
     */
    public static function extractTweetId($urlOrId)
    {
        if (preg_match('/^\d+$/', $urlOrId)) {
            // If the string is a number, return it right away
            return (int)$urlOrId;
        }

        if (preg_match('/\/status(es)?\/(\d+)\/?$/', $urlOrId, $matches)) {
            // Extract the tweet ID from the URL
            return (int)$matches[2];
        }

        return null;
    }

    /**
     * Formats a duration to seconds.
     *
     * @param string $duration
     *
     * @return int
     * @throws \Exception
     */
    public static function durationToSeconds($duration): int
    {
        $date = new DateTime;
        $current = $date->getTimestamp();
        $date->add(new DateInterval($duration));

        return $date->getTimestamp() - $current;
    }

    /**
     * Formats a date to a time ago string like “3 days ago”.
     *
     * @param DateTime|string $date
     *
     * @return string
     */
    public static function timeAgo($date): string
    {
        if (is_string($date)) {
            $date = new DateTime($date);
        }

        $now = new DateTime();

        $difference = $now->getTimestamp() - $date->getTimestamp();

        $duration = self::secondsToHumanTimeDuration($difference);

        return Craft::t('twitter', '{duration} ago', ['duration' => $duration]);
    }

    /**
     * Seconds to human duration.
     *
     * @param int $seconds The number of seconds.
     * @return string The duration.
     */
    public static function secondsToHumanTimeDuration($seconds): string
    {
        $periods = ['second', 'minute', 'hour', 'day', 'week', 'month', 'year', 'decade'];
        $lengths = ['60', '60', '24', '7', '4.35', '12', '10'];

        $difference = $seconds;

        for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths) - 1; $j++) {
            $difference /= $lengths[$j];
        }

        $difference = round($difference);

        if ($difference != 1) {
            $periods[$j] .= 's';
        }

        return Craft::t('twitter', '{difference} {period}', ['difference' => $difference, 'period' => $periods[$j]]);
    }
}
