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
class tx_communityflexiblelayout_saveDashboardCommand implements tx_communityflexiblelayout_CommandInterface {
	protected $commandName = 'saveDashboard';
	/**
	 * @var tx_community_ApplicationManager
	 */
	protected $communityApplicationManager;
	protected $widgets = array();
	protected $cols = array();
	protected $request;
	
	public function __construct() {
		$this->communityApplicationManager = tx_community_ApplicationManager::getInstance();
		$registry = tx_community_Registry::getInstance('tx_communityflexiblelayout');
		$this->conf = $registry->getConfiguration();
		$this->request = t3lib_div::_GP('tx_communityflexiblelayout');
	}

	public function execute() {
		$newConfig = $this->request['dashboardConfig'];
		if ($newConfig) {
			$dashboardConfig = serialize($newConfig);	
		}
		/**
		 * @var tx_communityflexiblelayout_LayoutManager
		 */
		$layoutManager = new tx_communityflexiblelayout_LayoutManager();
		
		$userGateway = new tx_community_model_UserGateway();
		$user = $userGateway->findCurrentlyLoggedInUser();
		$profileId = $user->getUid();
		$this->status = ($layoutManager->setConfiguration($this->conf['communityID'], $this->conf['profileType'], $profileId, $dashboardConfig)) ? 'saved' : 'error';
	}
	
	public function getJsonResponse() {
		return json_encode($this->status);
	}
	
	public function getCommandName() {
		return $this->commandName;
	}
}
?>