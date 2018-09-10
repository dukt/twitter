<?php

namespace dukt\twitter\migrations;

use Craft;
use craft\db\Migration;
use dukt\twitter\fields\Tweet;
use dukt\twitter\widgets\SearchWidget;

/**
 * m180910_122732_craft3_upgrade migration.
 */
class m180910_122732_craft3_upgrade extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        // Fields
        $this->update('{{%fields}}', [
            'type' => Tweet::class
        ], ['type' => 'Twitter_Tweet']);

        // Widgets
        $this->update('{{%widgets}}', [
            'type' => SearchWidget::class
        ], ['type' => 'Twitter_Search']);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180910_122732_craft3_upgrade cannot be reverted.\n";
        return false;
    }
}
