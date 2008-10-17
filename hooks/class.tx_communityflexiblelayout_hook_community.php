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
		
		$profileActions[] = $this->getAddAsScoolmateLink();
		return $profileActions;
	}
	
	protected function getAddAsScoolmateLink() {
		
		// @TODO: check if there is a relatiion with this role, change link to remove, edit, etc,
		
		$content = $this->profileActionsWidget->getCommunityApplication()->pi_linkToPage(
			$this->localizationManager->getLL('action_addAsScoolmate'),
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
		return array('link' => $content);
	}
}
?>