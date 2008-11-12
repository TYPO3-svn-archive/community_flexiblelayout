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

require_once(t3lib_extMgm::extPath('community').'classes/class.tx_community_localizationmanager.php');
require_once(t3lib_extMgm::extPath('community').'classes/class.tx_community_registry.php');
require_once(t3lib_extMgm::extPath('community').'interfaces/interface.tx_community_userprofileactionsprovider.php');
require_once(t3lib_extMgm::extPath('community').'interfaces/interface.tx_community_userprofileprovider.php');
require_once(t3lib_extMgm::extPath('community').'model/class.tx_community_model_usergateway.php');

class tx_communityflexiblelayout_hook_Community implements tx_community_UserProfileActionsProvider, tx_community_UserProfileProvider {
	/**
	 * @var tx_community_controller_userprofile_ProfileActionsWidget
	 */
	protected $profileActionsWidget;
	/**
	 * @var tx_community_LocalizationManager
	 */
	protected $localizationManager;
	protected $configuration;
	
	public function getUserProfileActions(array $profileActions, tx_community_controller_userprofile_ProfileActionsWidget $profileActionsWidget) {
		$this->profileActionsWidget = $profileActionsWidget;
		
		$localizationManagerClass = t3lib_div::makeInstanceClassName('tx_community_LocalizationManager');
		$this->localizationManager      = call_user_func(
			array($localizationManagerClass, 'getInstance'),
			'EXT:community_flexiblelayout/lang/locallang_application.xml',
			array()
		);
		
		$this->configuration = $this->profileActionsWidget->getConfiguration();
		
		$links = $this->getScoolmateLinks();
		
		foreach ($links as $link) {
			$profileActions[] = array('link' => $link);	
		}
		return $profileActions;
	}
	
	public function getProfileUid($uid, $profileModel) {
		$registry				= tx_community_Registry::getInstance('tx_communityflexiblelayout');
		$this->configuration	= $registry->getConfiguration();
		if (strlen($this->configuration['fixProfileType'])) {
			switch ($this->configuration['fixUser']) {
				case 'logedInUser':
					if ($this->loggedinUser !== null) {
						return $this->loggedinUser->getUid();
					}
				break;
				default:
					return (int) $this->configuration['fixUser'];
				break;
			}
		}
		return $uid;
	}
	
	protected function getScoolmateLinks() {
		$returnData = array();
		
		$requestedUser  = $this->profileActionsWidget->getCommunityApplication()->getRequestedUser();
		$requestingUser = $this->profileActionsWidget->getCommunityApplication()->getRequestingUser();
		
		if ($this->isScoolmate($requestingUser, $requestedUser)) {
			$returnData[] = $this->profileActionsWidget->getCommunityApplication()->pi_linkToPage(
				$this->localizationManager->getLL('action_removeAsFriend'),
				$GLOBALS['TSFE']->id,
				'',
				array(
					'tx_community' => array(
						'user' => $this->profileActionsWidget->getCommunityApplication()->getRequestedUser()->getUid(),
						'profileActionsAction' => 'removeAsFriend',
						'roleId' => 3
					)
				)
			); 
			$returnData[] = $this->profileActionsWidget->getCommunityApplication()->pi_linkToPage(
				$this->localizationManager->getLL('action_editRelationship'),
				$GLOBALS['TSFE']->id,
				'',
				array(
					'tx_community' => array(
						'user' => $this->profileActionsWidget->getCommunityApplication()->getRequestedUser()->getUid(),
						'profileActionsAction' => 'editRelationship',
						'roleId' => 3
					)
				)
			); 
		} else {
			$returnData[] = $this->profileActionsWidget->getCommunityApplication()->pi_linkToPage(
				$this->localizationManager->getLL('action_addAsFriend'),
				$GLOBALS['TSFE']->id,
				'',
				array(
					'tx_community' => array(
						'user' => $this->profileActionsWidget->getCommunityApplication()->getRequestedUser()->getUid(),
						'profileActionsAction' => 'addAsFriend',
						'roleId' => 3
					)
				)
			); 
		}
		
		
		return $returnData;
	}

	protected function isScoolmate(tx_community_model_User $user, tx_community_model_User $friend) {
		$isScoolmate = false;
		
		$userGateway	= t3lib_div::makeInstance('tx_community_model_UserGateway');
		$friendsOfUser	= $userGateway->findConnectedUsersByRole($user, 3);

		foreach ($friendsOfUser as $friendOfUser) {
			if ($friendOfUser->getUid() == $friend->getUid()) {
				$isScoolmate = true;
				break;
			}
		}
		return $isScoolmate;
	}
	
}
?>