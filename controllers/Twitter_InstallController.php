<?php
/**
 * @link      https://dukt.net/craft/twitter/
 * @copyright Copyright (c) 2017, Dukt
 * @license   https://dukt.net/craft/twitter/docs/license
 */

namespace Craft;

/**
 * Twitter Plugin controller
 */
class Twitter_InstallController extends BaseController
{
    // Public Methods
    // =========================================================================

    /**
     * Install Index
     *
     * @return null
     */
    public function actionIndex()
    {
        if(!craft()->twitter->checkDependencies())
        {
            $missingDependencies = craft()->twitter->getMissingDependencies();
            $this->renderTemplate('twitter/_special/install/index', [
                'missingDependencies' => $missingDependencies,
            ]);
        }
        else
        {
            $this->redirect('twitter/settings');
        }
    }
}
