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

require_once(t3lib_extMgm::extPath('community_flexiblelayout').'interfaces/class.tx_communityflexiblelayout_commandinterface.php');
require_once(t3lib_extMgm::extPath('community').'classes/class.tx_community_applicationmanager.php');
require_once(t3lib_extMgm::extPath('community').'classes/class.tx_community_registry.php');
require_once(t3lib_extMgm::extPath('community_flexiblelayout').'classes/class.tx_communityflexiblelayout_layoutmanager.php');
require_once(t3lib_extMgm::extPath('community').'model/class.tx_community_model_usergateway.php');

/**
 * Show Dashboard Command (model)
 *
 * @author	Frank Nägler <typo3@naegler.net>
 * @package TYPO3
 * @subpackage community_flexiblelayout
 */
class tx_communityflexiblelayout_editDashboardCommand implements tx_communityflexiblelayout_CommandInterface {
	protected $commandName = 'editDashboard';
	/**
	 * @var tx_community_ApplicationManager
	 */
	protected $communityApplicationManager;
	/**
	 * @var tx_community_model_UserGateway
	 */
	protected $userGateway;
	protected $widgets = array();
	protected $cols = array();

	public function __construct() {
		$this->communityApplicationManager = tx_community_ApplicationManager::getInstance();
		$registry = tx_community_Registry::getInstance('tx_communityflexiblelayout');
		$this->conf = $registry->getConfiguration();
		$this->request = t3lib_div::_GP('tx_community');
		$this->userGateway = new tx_community_model_UserGateway();
	}

	public function execute() {
		$widgets = $this->communityApplicationManager->getWidgetsByApplication($this->conf['profileID']);
		foreach ($widgets as $widgetName => $widget) {
			if ($widget instanceof tx_community_CommunityApplicationWidget) {
				$this->widgets[$widgetName] = $widget;
			}
		}
		/**
		 * @var tx_communityflexiblelayout_LayoutManager
		 */
		$layoutManager = new tx_communityflexiblelayout_LayoutManager();

		/**
		 * @var tx_community_model_User
		 */
		$loggedinUser = $this->userGateway->findCurrentlyLoggedInUser();

		$profileId = $loggedinUser->getUid();
		$profileId = (intval($this->request['user'])) ? intval($this->request['user']) : $profileId;

		$config = $layoutManager->getConfiguration($this->conf['communityID'], $this->conf['profileID'], $profileId);
		$config = unserialize($config);
		if (is_array($config)) {
			foreach($config as $c) {
				$parts = t3lib_div::trimExplode(',', $c);
				$newConfig[$parts[2]] = array(
					'col'	=> $parts[0],
					'pos'	=> $parts[1],
					'id'	=> $parts[2]
				);
			}
			$config = $newConfig;
		} else {
			$config = array();
		}

		foreach ($this->widgets as $widgetName => $widget) {
			if (is_array($config[$widgetName])) {
				$this->cols[$config[$widgetName]['col']][$config[$widgetName]['pos']] = $widget;
			} else {
				$this->cols[$widget->getLayoutContainer()][] = $widget;
			}
		}
	}

	public function getCommandName() {
		return $this->commandName;
	}

	public function getWidgetsByCol($col) {
		return $this->cols[$col];
	}

	public function getAllWidgets() {
		return $this->cols;
	}
}
?>