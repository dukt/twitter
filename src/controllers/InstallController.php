<?php
/**
 * @link      https://dukt.net/craft/twitter/
 * @copyright Copyright (c) 2017, Dukt
 * @license   https://dukt.net/craft/twitter/docs/license
 */

namespace dukt\twitter\controllers;

use craft\web\Controller;

/**
 * Install controller
 *
 * @author Dukt <support@dukt.net>
 * @since  3.0
 */
class InstallController extends Controller
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
            return $this->renderTemplate('twitter/_special/install/index', [
                'missingDependencies' => $missingDependencies,
            ]);
        }
        else
        {
            return $this->redirect('twitter/settings');
        }
    }
}
