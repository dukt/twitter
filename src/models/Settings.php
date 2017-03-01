<?php
/**
 * @link      https://dukt.net/craft/twitter/
 * @copyright Copyright (c) 2017, Dukt
 * @license   https://dukt.net/craft/twitter/docs/license
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
     * @var
     */
    public $token;

    /**
     * @var
     */
    public $tokenSecret;

    /**
     * @var
     */
    public $oauthConsumerKey;

    /**
     * @var
     */
    public $oauthConsumerSecret;

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
