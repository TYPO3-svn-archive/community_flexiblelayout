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

require_once(t3lib_extMgm::extPath('community') . 'controller/userprofile/class.tx_community_controller_userprofile_personalinformationwidget.php');

/**
 * personal information widget for the user profile community application
 * showing interests, activities, favorites, and about me
 *
 * @author	Frank Nägler <typo3@naegler.net>
 * @package TYPO3
 * @subpackage community
 */
class tx_communityflexiblelayout_controller_PersonalInformationWidget extends tx_community_controller_userprofile_PersonalInformationWidget {

	public function __construct() {
		parent::__construct();
		$this->localizationManager = tx_community_LocalizationManager::getInstance('EXT:community_flexiblelayout/lang/locallang_application.xml', $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_communityflexiblelayout.']);

		$this->name     = 'personalInformation2';
		$this->label    = $this->localizationManager->getLL('label_PersonalInformationWidget');

		$this->draggable = true;
		$this->removable = true;
		$this->position  = 2;
	}

	/**
	 * the default action for this widget, fetches the user to show the personal
	 * information for, creates a view and returns the view's output
	 *
	 * @return	string	the view's output
	 */
	public function indexAction() {
			// TODO move this into a indexAction() method
		$content = '';

		$requestedUser  = $this->communityApplication->getRequestedUser();
		$requestingUser = $this->communityApplication->getRequestingUser();

		$accessManagerClass = t3lib_div::makeInstanceClassName('tx_community_AccessManager');
		$accessManager      = call_user_func(array($accessManagerClass, 'getInstance'));

		$allowed = false;
		if ($requestedUser == $requestingUser) {
			$allowed = true;
		} else {
			$accessManager->addResource($this);
			$allowed = $accessManager->isAllowed($this);
		}

		if ($allowed) {
			$view = t3lib_div::makeInstance('tx_community_view_userprofile_PersonalInformation');
			$view->setUserModel($requestedUser);
			$view->setTemplateFile($this->configuration['applications.']['userProfile.']['widgets.']['personalInformation2.']['templateFile']);
			$view->setLanguageKey($this->communityApplication->LLkey);

			$content = $view->render();
		}

		return $content;
	}
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/community_flexiblelayout/controller/class.tx_communityflexiblelayout_controller_personalinformationwidget.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/community_flexiblelayout/controller/class.tx_communityflexiblelayout_controller_personalinformationwidget.php']);
}

?>