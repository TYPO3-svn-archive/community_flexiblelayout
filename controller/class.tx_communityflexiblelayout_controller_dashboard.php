<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2008 Frank Nägler <typo3@naegler.net>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

require_once(t3lib_extMgm::extPath('community_flexiblelayout').'classes/class.tx_communityflexiblelayout_commandresolver.php');
require_once(t3lib_extMgm::extPath('community_flexiblelayout').'view/class.tx_communityflexiblelayout_showdashboardview.php');
require_once(t3lib_extMgm::extPath('community_flexiblelayout').'view/class.tx_communityflexiblelayout_editdashboardview.php');
require_once(t3lib_extMgm::extPath('community_flexiblelayout').'view/class.tx_communityflexiblelayout_savedashboardview.php');
require_once(t3lib_extMgm::extPath('community_flexiblelayout').'view/class.tx_communityflexiblelayout_errorview.php');
require_once(t3lib_extMgm::extPath('community').'classes/class.tx_community_registry.php');

/**
 * Dashboard Controller
 *
 * @author	Frank Nägler <typo3@naegler.net>
 * @package TYPO3
 * @subpackage community_flexiblelayout
 */
class tx_communityflexiblelayout_controller_Dashboard {
	public $prefixId      = 'tx_communityflexiblelayout_controller_Dashboard';		// Same as class name
	public $scriptRelPath = 'controller/class.tx_communityflexiblelayout_controller_dashboard.php';	// Path to this script relative to the extension dir.
	public $extKey        = 'community_flexiblelayout';	// The extension key.

	/**
	 * constructor for class tx_communityflexiblelayout_controller_Dashboard
	 */
	public function __construct() {
	}

	public function execute($content, array $configuration) {
		$registry = tx_community_Registry::getInstance('tx_communityflexiblelayout');
		$registry->setConfiguration($configuration);
		
		$cmdResolver = new tx_communityflexiblelayout_CommandResolver($configuration['defaultCommand']);
		try {
			$model = $cmdResolver->getCommand();
			$model->execute();
			$cmdName = $model->getCommandName();
			$viewName = "tx_communityflexiblelayout_".ucfirst($cmdName)."View";
		} catch (tx_communityflexiblelayout_NoProfileIdException $exception) {
			$viewName = 'tx_communityflexiblelayout_ErrorView';
			$model = $exception;
		} catch (tx_communityflexiblelayout_UnknownProfileException $exception) {
			$viewName = 'tx_communityflexiblelayout_ErrorView';
			$model = $exception;
		} catch (Exception $exception) {
			die ('unhandled exception: ' . $exception);
		}
		
		$view = new $viewName($model);
		return $view->render();
	}
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MOlDE]['XCLASS']['ext/community_flexiblelayout/controller/class.tx_communityflexiblelayout_controller_dashboard.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/community_flexiblelayout/controller/class.tx_communityflexiblelayout_controller_dashboard.php']);
}

?>