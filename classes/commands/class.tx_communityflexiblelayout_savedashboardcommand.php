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
	
	public function __construct() {
		$this->communityApplicationManager = tx_community_ApplicationManager::getInstance();
		$registry = tx_donation_Registry::getInstance('');
		$this->conf = $registry->get('configuration');
	}

	public function execute() {
		$newConfig = t3lib_div::_GP('dashboardConfig');
		if ($newConfig) {
			$tmp[$this->conf['communityID']][$this->conf['profileID']] = $newConfig;
			print_r($tmp);
			$dashboardConfig = serialize($tmp);	
		}
		$GLOBALS['TYPO3_DB']->exec_UPDATEquery(
			'fe_users',
			'uid = ' . $GLOBALS['TSFE']->fe_user->user['uid'],
			array('tx_communityflexiblelayout_dashboardconfig' => $dashboardConfig)
		);
		$this->status = ($GLOBALS['TYPO3_DB']->sql_affected_rows() > 0) ? 'saved' : 'error';
	}
	
	public function getJsonResponse() {
		return json_encode($this->status);
	}
	
	public function getCommandName() {
		return $this->commandName;
	}
}
?>