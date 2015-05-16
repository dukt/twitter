<?php
/**
 * @link      https://dukt.net/craft/twitter/
 * @copyright Copyright (c) 2015, Dukt
 * @license   https://dukt.net/craft/twitter/docs/license
 */

namespace Craft;

use Guzzle\Http\Client;

class Twitter_PluginService extends BaseApplicationComponent
{
    // Public Methods
    // =========================================================================

    /**
     * Download
     */
    public function download($pluginHandle)
    {
        Craft::log(__METHOD__, LogLevel::Info, true);

        // -------------------------------
        // Get ready to download & unzip
        // -------------------------------

        $return = array('success' => false);

        $pluginComponent = craft()->plugins->getPlugin($pluginHandle, false);


        // plugin path

        $pluginZipDir = CRAFT_PLUGINS_PATH."_".$pluginHandle."/";
        $pluginZipPath = CRAFT_PLUGINS_PATH."_".$pluginHandle.".zip";


        // remote plugin zip url

        $remotePlugin = $this->_getRemotePlugin($pluginHandle);

        if(!$remotePlugin) {
            $return['msg'] = "Couldn't get plugin last version";

            Craft::log(__METHOD__.' : Could not get last version' , LogLevel::Info, true);

            return $return;
        }

        $remotePluginZipUrl = $remotePlugin['xml']->enclosure['url'];

        // -------------------------------
        // Download & Install
        // -------------------------------

        try {

            // download remotePluginZipUrl to pluginZipPath

            $client = new \Guzzle\Http\Client();
            $request = $client->get($remotePluginZipUrl);
            $response = $request->send();
            $body = $response->getBody();

            // Make sure we're at the beginning of the stream.
            $body->rewind();

            // Write it out to the file
            IOHelper::writeToFile($pluginZipPath, $body->getStream(), true);

            // Close the stream.
            $body->close();

            // unzip pluginZipPath into pluginZipDir

            Zip::unzip($pluginZipPath, $pluginZipDir);
            $contents = IOHelper::getFiles($pluginZipDir);

            $pluginUnzipDir = false;

            foreach($contents as $content)
            {
                if(strrpos($content, "__MACOSX") !== 0)
                {
                    $pluginUnzipDir = $content;
                }
            }

            // move files we want to keep from their current location to unzipped location
            // keep : .git

            if(file_exists(CRAFT_PLUGINS_PATH.$pluginHandle.'/.git') && !$pluginUnzipDir.'/.git') {
                IOHelper::rename(CRAFT_PLUGINS_PATH.$pluginHandle.'/.git',
                    $pluginUnzipDir.'/.git');
            }

            //rename($path, $newName, $suppressErrors = false)


            // remove current files
            // make a backup of existing plugin (to storage ?) ?
            //deleteFolder($path, $suppressErrors = false)
            IOHelper::deleteFolder(CRAFT_PLUGINS_PATH.$pluginHandle);


            // move new files to final destination

            IOHelper::rename($pluginUnzipDir.'/'.$pluginHandle.'/', CRAFT_PLUGINS_PATH.$pluginHandle);

            // delete zip
            IOHelper::deleteFile($pluginZipPath);

        } catch (\Exception $e) {

            $return['msg'] = $e->getMessage();

            Craft::log(__METHOD__.' : Crashed : '.$e->getMessage() , LogLevel::Info, true);

            return $return;
        }


        // remove download files

        try {
            IOHelper::deleteFolder($pluginZipDir);
            IOHelper::deleteFolder($pluginZipPath);
        } catch(\Exception $e) {

            $return['msg'] = $e->getMessage();

            Craft::log(__METHOD__.' : Crashed : '.$e->getMessage() , LogLevel::Info, true);

            return $return;
        }

        Craft::log(__METHOD__.' : Success : ' , LogLevel::Info, true);

        $return['success'] = true;

        return $return;
    }

    /**
     * Enable
     */
    public function enable($pluginHandle)
    {
        Craft::log(__METHOD__, LogLevel::Info, true);

        $pluginComponent = craft()->plugins->getPlugin($pluginHandle, false);

        try {

            if(!$pluginComponent->isEnabled) {
                if (craft()->plugins->enablePlugin($pluginHandle)) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return true;
            }

        } catch(\Exception $e) {

            Craft::log(__METHOD__.' : Crashed : '.$e->getMessage(), LogLevel::Info, true);

            return false;
        }
    }

    /**
     * Install
     */
    public function install($pluginHandle)
    {
        Craft::log(__METHOD__, LogLevel::Info, true);

        craft()->plugins->loadPlugins();

        $pluginComponent = craft()->plugins->getPlugin($pluginHandle, false);

        try {
            if(!$pluginComponent)
            {
                Craft::log(__METHOD__.' : '.$pluginHandle.' component not found', LogLevel::Info, true);

                return false;
            }

            if(!$pluginComponent->isInstalled) {
                if (craft()->plugins->installPlugin($pluginHandle)) {
                    return true;
                } else {

                    Craft::log(__METHOD__.' : '.$pluginHandle.' component not installed', LogLevel::Info, true);

                    return false;
                }
            } else {
                return true;
            }
        } catch(\Exception $e) {

            Craft::log(__METHOD__.' : Crashed : '.$e->getMessage(), LogLevel::Info, true);

            return false;
        }
    }

    // Private Methods
    // =========================================================================

    /**
     * Get Remote Plugin
     */
    private function _getRemotePlugin($pluginHandle)
    {
        Craft::log(__METHOD__, LogLevel::Info, true);

        $url = 'https://dukt.net/craft/'.$pluginHandle.'/releases.xml';


        // or refresh cache and get new updates if cache expired or forced update


        $client = new Client();
        $response = $client->get($url)->send();

        $xml = $response->xml();

        // XML from here on

        $namespaces = $xml->getNameSpaces(true);

        $versions = array();
        $zips = array();
        $xml_version = array();

        if (!empty($xml->channel->item)) {
            foreach ($xml->channel->item as $version) {
                $ee_addon       = $version->children($namespaces['ee_addon']);
                $version_number = (string) $ee_addon->version;
                $versions[$version_number] = array('xml' => $version, 'addon' => $ee_addon);
                return $versions[$version_number];
            }
        } else {
            Craft::log(__METHOD__.' : Could not get channel items', LogLevel::Info, true);
        }
    }
}