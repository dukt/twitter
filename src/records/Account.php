<?php
/**
 * @link      https://dukt.net/twitter/
 * @copyright Copyright (c) Dukt
 * @license   https://github.com/dukt/twitter/blob/master/LICENSE.md
 */

namespace dukt\twitter\records;

use craft\db\ActiveRecord;

/**
 * Account record.
 *
 * @property int $id
 * @property string $token
 * @property string $tokenSecret
 */
class Account extends ActiveRecord
{
    // Public Methods
    // =========================================================================

    /**
     * Returns the name of the associated database table.
     *
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%twitter_accounts}}';
    }
}
