<?php

namespace dukt\twitter\migrations;

use craft\db\Migration;

/**
 * m170830_090841_craft3_upgrade migration.
 */
class m170830_090841_craft3_upgrade extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->update('{{%widgets}}', ['type' => 'dukt\twitter\widgets\SearchWidget'], ['type' => 'Twitter_SearchWidget']);

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m170830_090841_craft3_upgrade cannot be reverted.\n";
        return false;
    }
}
