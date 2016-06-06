<?php
/**
 * @link      https://dukt.net/craft/twitter/
 * @copyright Copyright (c) 2016, Dukt
 * @license   https://dukt.net/craft/twitter/docs/license
 */

namespace Twitter;

class AutoLink extends \Twitter_Autolink
{
    // Public Methods
    // =========================================================================

    /**
     * Provides fluent method chaining.
     *
     * @param  string  $tweet        The tweet to be converted.
     * @param  bool    $full_encode  Whether to encode all special characters.
     *
     * @see  __construct()
     *
     * @return  Twitter\AutoLink
     */
    public static function create($tweet = null, $full_encode = false)
    {
        return new self($tweet, $full_encode);
    }

    /**
    * Autolink with entities
    *
    * @param string $tweet
    * @param array $entities
    * @return string
    * @since 1.1.0
    */
    public function autoLinkEntities($tweet = null, $entities)
    {
        if (is_null($tweet))
        {
            $tweet = $this->tweet;
        }

        $text = '';
        $beginIndex = 0;

        foreach ($entities as $entity)
        {
            if (isset($entity['screen_name']))
            {
                $text .= mb_substr($tweet, $beginIndex, $entity['indices'][0] - $beginIndex);
            }
            else {
                $text .= mb_substr($tweet, $beginIndex, $entity['indices'][0] - $beginIndex);
            }

            if (isset($entity['url']))
            {
                $text .= $this->linkToUrl($entity);
            }
            elseif (isset($entity['hashtag']))
            {
                $text .= $this->linkToHashtag($entity, $tweet);
            }
            elseif (isset($entity['screen_name']))
            {
                $text .= $this->linkToMentionAndList($entity);
            }
            elseif (isset($entity['cashtag']))
            {
                $text .= $this->linkToCashtag($entity, $tweet);
            }

            $beginIndex = $entity['indices'][1];
        }

        $text .= mb_substr($tweet, $beginIndex, mb_strlen($tweet));

        return $text;
    }

    /**
     *
     * @param array  $entity
     * @return string
     * @since 1.1.0
     */
    public function linkToMentionAndList($entity)
    {
        $attributes = array();

        if (!empty($entity['list_slug']))
        {
            # Replace the list and username
            $linkText = $entity['screen_name'] . $entity['list_slug'];
            $class = $this->class_list;
            $url = $this->url_base_list . $linkText;
        }
        else
        {
            # Replace the username
            $linkText = '@'.$entity['screen_name'];
            $class = $this->class_user;
            $url = $this->url_base_user . $linkText;
        }

        if (!empty($class))
        {
            $attributes['class'] = $class;
        }

        $attributes['href'] = $url;

        return $this->linkToText($entity, $linkText, $attributes);
    }
}