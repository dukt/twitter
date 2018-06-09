<?php
/**
 * @link      https://dukt.net/craft/twitter/
 * @copyright Copyright (c) 2018, Dukt
 * @license   https://dukt.net/craft/twitter/docs/license
 */

namespace dukt\twitter\lib;

/**
 * Class Auto Link
 *
 * @author Dukt <support@dukt.net>
 * @since  3.0
 */
class AutoLink extends \Twitter\Text\Autolink
{
    // Properties
    // =========================================================================

    /**
     * Whether to include the value 'noopener' in the 'rel' attribute.
     *
     * @var  bool
     */
    protected $noopener = true;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public static function create($tweet = null, $full_encode = false)
    {
        return new self($tweet, $full_encode);
    }

    /**
     * @inheritdoc
     */
    public function autoLinkEntities($tweet = null, $entities = null)
    {
        if (is_null($tweet)) {
            $tweet = $this->tweet;
        }

        $text = '';
        $beginIndex = 0;

        foreach ($entities as $entity) {
            if (isset($entity['screen_name'])) {
                $text .= mb_substr($tweet, $beginIndex, $entity['indices'][0] - $beginIndex);
            } else {
                $text .= mb_substr($tweet, $beginIndex, $entity['indices'][0] - $beginIndex);
            }

            if (isset($entity['url'])) {
                $text .= $this->linkToUrl($entity);
            } elseif (isset($entity['hashtag'])) {
                $text .= $this->linkToHashtag($entity, $tweet);
            } elseif (isset($entity['screen_name'])) {
                $text .= $this->linkToMentionAndList($entity);
            } elseif (isset($entity['cashtag'])) {
                $text .= $this->linkToCashtag($entity, $tweet);
            }

            $beginIndex = $entity['indices'][1];
        }

        $text .= mb_substr($tweet, $beginIndex, mb_strlen($tweet));

        return $text;
    }

    /**
     * @inheritdoc
     */
    public function linkToMentionAndList($entity)
    {
        $attributes = [];

        if (!empty($entity['list_slug'])) {
            # Replace the list and username
            $linkText = $entity['screen_name'].$entity['list_slug'];
            $class = $this->class_list;
            $url = $this->url_base_list.$linkText;
        } else {
            # Replace the username
            $linkText = '@'.$entity['screen_name'];
            $class = $this->class_user;
            $url = $this->url_base_user.$linkText;
        }

        if (!empty($class)) {
            $attributes['class'] = $class;
        }

        $attributes['href'] = $url;

        return $this->linkToText($entity, $linkText, $attributes);
    }

    /**
     * @inheritdoc
     */
    public function linkToText(array $entity, $text, $attributes = [])
    {
        $rel = [];
        if ($this->external) {
            $rel[] = 'external';
        }
        if ($this->nofollow) {
            $rel[] = 'nofollow';
        }
        if ($this->noopener) {
            $rel[] = 'noopener';
        }
        if (!empty($rel)) {
            $attributes['rel'] = join(' ', $rel);
        }
        if ($this->target) {
            $attributes['target'] = $this->target;
        }
        $link = '<a';
        foreach ($attributes as $key => $val) {
            $link .= ' '.$key.'="'.$this->escapeHTML($val).'"';
        }
        $link .= '>'.$text.'</a>';

        return $link;
    }

    /**
     * Whether to include the value 'noopener' in the 'rel' attribute.
     *
     * @return  bool  Whether to add 'noopener' to the 'rel' attribute.
     */
    public function getNoOpener()
    {
        return $this->noopener;
    }

    /**
     * Whether to include the value 'noopener' in the 'rel' attribute.
     *
     * @param  bool $v The value to add to the 'target' attribute.
     *
     * @return  Autolink  Fluid method chaining.
     */
    public function setNoOpener($v)
    {
        $this->noopener = $v;

        return $this;
    }
}