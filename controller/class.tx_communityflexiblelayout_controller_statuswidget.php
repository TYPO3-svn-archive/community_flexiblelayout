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

require_once(t3lib_extMgm::extPath('community') . 'controller/class.tx_community_controller_abstractcommunityapplicationwidget.php');

/**
 * status information widget for the persoal startpage
 * showing interests, activities, favorites, and about me
 *
 * @author	Frank Nägler <typo3@naegler.net>
 * @package TYPO3
 * @subpackage community
 */
class tx_communityflexiblelayout_controller_StatusWidget extends tx_community_controller_AbstractCommunityApplicationWidget {

	public function __construct() {
		parent::__construct();
		$this->localizationManager = tx_community_LocalizationManager::getInstance('EXT:community_flexiblelayout/lang/locallang_application.xml', $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_communityflexiblelayout.']);

		$this->name     = 'statusWidget';
		$this->label    = $this->localizationManager->getLL('label_StatusWidget');

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
		$content = '';

		$requestingUser = $this->communityApplication->getRequestingUser();
		
		$rows = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
			'DISTINCT f1.friend',
			'tx_community_friend as f1 JOIN tx_community_friend AS f2'
			. ' ON f1.feuser = f2.friend
				AND f1.friend <> f2.feuser
				AND f1.friend = ' . $requestingUser->getUid(),
			''
		);
		debug($rows);
		
		$view = t3lib_div::makeInstance('tx_communitflexiblelayouty_view_StatusWidget');
		$view->setUserModel($requestingUser);
		$view->setOpenFriendRequestCount(count($rows));
		$view->setTemplateFile($this->configuration['applications.']['StartPage.']['widgets.']['statusWidget.']['templateFile']);
		$view->setLanguageKey($this->communityApplication->LLkey);

		$content = $view->render();
		
		return $content;
	}
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/community_flexiblelayout/controller/class.tx_communityflexiblelayout_controller_statuswidget.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/community_flexiblelayout/controller/class.tx_communityflexiblelayout_controller_statuswidget.php']);
}

?>