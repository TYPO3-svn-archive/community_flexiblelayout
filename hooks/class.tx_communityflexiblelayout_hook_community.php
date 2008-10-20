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

require_once(t3lib_extMgm::extPath('community').'interfaces/interface.tx_community_userprofileactionsprovider.php');
require_once(t3lib_extMgm::extPath('community').'classes/class.tx_community_localizationmanager.php');

class tx_communityflexiblelayout_hook_Community implements tx_community_UserProfileActionsProvider {
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
			$profileActions[] = array('link', $link);	
		}
		return $profileActions;
	}
	
	protected function getScoolmateLinks() {
		// @TODO: check if there is a relatiion with this role, change link to remove, edit, etc,
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
			// TODO this schould at some time be moved to a more appropriate place like a FriendshipManager or so
			// TODO: Question: I think this should be a method of the user object: isInRelationTo($user, $role) ?
		$isFriend = false;

		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'f1.uid',
			'tx_community_friend AS f1 JOIN tx_community_friend AS f2
				ON f1.feuser = f2.friend AND f1.friend = f2.feuser
				AND f1.feuser = ' . $user->getUid()
			. ' AND f1.friend = ' . $friend->getUid()
			. ' AND f1.role = 3',
			''
		);

		$friendConnectionCount = $GLOBALS['TYPO3_DB']->sql_num_rows($res);

		if ($friendConnectionCount > 0) {
			$isFriend = true;
		}

		return $isFriend;
	}
	
}
?>