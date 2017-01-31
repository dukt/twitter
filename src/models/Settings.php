<?php
namespace dukt\twitter\models;

use Craft;
use craft\base\Model;

class Settings extends Model
{
    // Public Properties
    // =========================================================================

    public $tokenId;

    // Public Methods
    // =========================================================================

    public function rules()
    {
        return [
            [['tokenId'], 'number', 'integerOnly' => true],
        ];
    }
}
