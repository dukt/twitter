<?php
/**
 * @link      https://dukt.net/twitter/
 * @copyright Copyright (c) 2021, Dukt
 * @license   https://github.com/dukt/twitter/blob/master/LICENSE.md
 */

namespace dukt\twitter\models;

use craft\base\Model;

/**
 * Account model class.
 *
 * @author Dukt <support@dukt.net>
 * @since  3.0
 */
class Account extends Model
{
    // Properties
    // =========================================================================

    public $id;

    public $token;

    public $tokenSecret;
}
