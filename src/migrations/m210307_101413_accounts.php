<?php

namespace dukt\twitter\migrations;

use Craft;
use craft\db\Migration;
use craft\db\TableSchema;
use dukt\twitter\Plugin;

/**
 * m210307_101413_accounts migration.
 */
class m210307_101413_accounts extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        /** @var TableSchema|null $tableSchema */
        $tableSchema = Craft::$app->db->schema->getTableSchema('{{%twitter_accounts}}');

        if ($tableSchema !== null) {
            return null;
        }

        $this->createTable(
            '{{%twitter_accounts}}',
            [
                'id'                => $this->primaryKey(),
                'token'             => $this->string(50),
                'tokenSecret'       => $this->string(50),
                'dateCreated'       => $this->dateTime()->notNull(),
                'dateUpdated'       => $this->dateTime()->notNull(),
                'uid'               => $this->uid()
            ]
        );

        // If OAuth token is set on the settings, keep it
        $settings = Plugin::$plugin->getSettings();

        if (
            !(property_exists($settings, 'token') && $settings->token !== null) ||
            !(property_exists($settings, 'tokenSecret') && $settings->tokenSecret !== null) ||
            !$settings->token ||
            !$settings->tokenSecret
        ) {
            return null;
        }

        $this->insert(
        '{{%twitter_accounts}}',
            [
                'token' => $settings->token,
                'tokenSecret' => $settings->tokenSecret,
            ]
        );

        // Reset token and token secret on the settings
        $settings->token = null;
        $settings->tokenSecret = null;

        $plugin = Craft::$app->getPlugins()->getPlugin('twitter');
        Craft::$app->getPlugins()->savePluginSettings($plugin, $settings->toArray());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m210307_101413_accounts cannot be reverted.\n";
        return false;
    }
}
