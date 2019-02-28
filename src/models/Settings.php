<?php
/**
 * @link      https://dukt.net/twitter/
 * @copyright Copyright (c) 2019, Dukt
 * @license   https://github.com/dukt/twitter/blob/master/LICENSE.md
 */

namespace dukt\twitter\models;

use craft\base\Model;

/**
 * Settings model class.
 *
 * @author Dukt <support@dukt.net>
 * @since  3.0
 */
class Settings extends Model
{
    // Properties
    // =========================================================================

    /**
     * @var string The amount of time cache should last
     *
     * @see http://www.php.net/manual/en/dateinterval.construct.php
     */
    public $cacheDuration = 'PT10M';

    /**
     * @var bool Whether request to APIs should be cached or not
     */
    public $enableCache = true;

    /**
     * @var string|null The OAuth token.
     */
    public $token;

    /**
     * @var string|null The OAuth token secret.
     */
    public $tokenSecret;

    /**
     * @var string|null The OAuth consumer key.
     */
    public $oauthConsumerKey;

    /**
     * @var string|null The OAuth consumer secret.
     */
    public $oauthConsumerSecret;

    /**
     * @var string|null The search widget’s extra query.
     */
    public $searchWidgetExtraQuery = '-filter:retweets';

    // Public Methods
    // =========================================================================

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['token', 'tokenSecret'], 'string'],
        ];
    }
}
