<?php
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
    public $tokenId;

    /**
     * @var
     */
    public $token;

    /**
     * @var
     */
    public $tokenSecret;

    // Public Methods
    // =========================================================================

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['tokenId'], 'number', 'integerOnly' => true],
            [['token', 'tokenSecret'], 'string'],
        ];
    }
}
