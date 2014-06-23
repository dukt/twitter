<?php
namespace Craft;

/**
 * The class name is the UTC timestamp in the format of mYYMMDD_HHMMSS_pluginHandle_migrationName
 */
class m140620_042750_twitter_transfer_token extends BaseMigration
{
    /**
     * Any migration code in here is wrapped inside of a transaction.
     *
     * @return bool
     */
    public function safeUp()
    {
        $this->transferSystemToken('twitter.system');

        return true;
    }

    private function saveToken($token)
    {
        craft()->twitter->saveToken($token);
    }

    private function transferSystemToken($namespace)
    {
        try {

            if(file_exists(CRAFT_PLUGINS_PATH.'oauth/vendor/autoload.php'))
            {
                require_once(CRAFT_PLUGINS_PATH.'oauth/vendor/autoload.php');
            }

            if(class_exists('OAuth\OAuth1\Token\StdOAuth1Token'))
            {
                // get token record

                $row = craft()->db->createCommand()
                    ->select('*')
                    ->from('oauth_old_tokens')
                    ->where('namespace = :namespace', array(':namespace' => $namespace))
                    ->queryRow();

                if($row)
                {
                    // transform token

                    $token = @unserialize(base64_decode($row['token']));

                    if($token)
                    {
                        // oauth 1
                        $newToken = new \OAuth\OAuth1\Token\StdOAuth1Token();
                        $newToken->setAccessToken($token->access_token);
                        $newToken->setRequestToken($token->access_token);
                        $newToken->setRequestTokenSecret($token->secret);
                        $newToken->setAccessTokenSecret($token->secret);

                        $this->saveToken($newToken);
                    }
                    else
                    {
                        Craft::log('Token error.', LogLevel::Info, true);
                    }
                }
                else
                {
                    Craft::log('Token record error.', LogLevel::Info, true);
                }
            }
            else
            {
                Craft::log('Class error.', LogLevel::Info, true);
            }
        }
        catch(\Exception $e)
        {
            Craft::log($e->getMessage(), LogLevel::Info, true);
        }
    }
}
