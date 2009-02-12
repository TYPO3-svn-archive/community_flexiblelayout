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

require_once(t3lib_extMgm::extPath('community') . 'model/class.tx_community_model_usergateway.php');
require_once(t3lib_extMgm::extPath('community') . 'classes/class.tx_community_localizationmanager.php');

require_once(t3lib_extMgm::extPath('community_flexiblelayout') . 'view/class.tx_communityflexiblelayout_view_connectionmanagerindex.php');

/**
 * Connection Manager Application Controller
 *
 * @author	Frank Naegler <typo3@naegler.net>
 * @package TYPO3
 * @subpackage community
 */
class tx_communityflexiblelayout_controller_ConnectionManagerApplication extends tx_community_controller_AbstractCommunityApplication {
	/**
	 * @var tx_community_LocalizationManager
	 */
	protected $llManager;
	
	/**
	 * constructor for class tx_communityflexiblelayout_controller_ConnectionManagerApplication
	 */
	public function __construct() {
		parent::__construct();

		$this->prefixId = 'tx_communityflexiblelayout_controller_ConnectionManagerApplication';
		$this->scriptRelPath = 'controller/class.tx_communityflexiblelayout_controller_connectionmanagerapplication.php';
		$this->name = 'connectionManager';

		$llMangerClass = t3lib_div::makeInstanceClassName('tx_community_LocalizationManager');
		$this->llManager = call_user_func(array($llMangerClass, 'getInstance'), 'EXT:community_flexiblelayout/lang/locallang_connectionmanager.xml',	$GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_communityflexiblelayout.']);
	}

		// TODO refactor this method
	public function indexAction() {
		$roleIds = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
			'uid, name',
			'tx_community_acl_role',
			'is_friend_role = 1'
		);
		$openFriendRequests = array();
		foreach ($roleIds as $roleId) {
			$openFriendRequests = array_merge($openFriendRequests, $this->getUserGateway()->findUnconfirmedFriends(null, $roleId['uid']));	
		}
		

			// now use the user list to present the result
		$userList = $GLOBALS['TX_COMMUNITY']['applicationManager']->getApplication(
			'userList',
			$this->data,
			$this->configuration
		);
		$userList->setUserListModel($openFriendRequests);
		return $userList->execute();
	}
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/community/controller/class.tx_community_controller_editgroupapplication.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/community/controller/class.tx_community_controller_editgroupapplication.php']);
}

?>