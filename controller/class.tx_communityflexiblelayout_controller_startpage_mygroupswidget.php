<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2008 Frank Naegler <typo3@naegler.net>
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

require_once(t3lib_extMgm::extPath('community') . 'controller/class.tx_community_controller_userprofile_mygroupswidget.php');

/**
 * a widget for the personal startpage to show the user's group memberships
 *
 * @author	Frank Naegler <typo3@naegler.net>
 * @package TYPO3
 * @subpackage community_flexiblelayout
 */
class tx_communityflexiblelayout_controller_startpage_MyGroupsWidget extends tx_community_controller_userprofile_MyGroupsWidget {

	/**
	 * constructor for class tx_communityflexiblelayout_controller_startpage_MyGroupsWidget
	 */
	public function __construct() {
		parent::__construct();
	}

	public function indexAction() {
		$groupGateway = t3lib_div::makeInstance('tx_community_model_GroupGateway');
		$groups = $groupGateway->findGroupsByUser(
			$this->communityApplication->getRequestingUser()
		);

		$view = t3lib_div::makeInstance('tx_community_view_userprofile_MyGroups');
		$view->setTemplateFile($this->configuration['applications.']['userProfile.']['widgets.']['myGroups.']['templateFile']);
		$view->setLanguageKey($this->communityApplication->LLkey);
		$view->setGroupModel($groups);

		return $view->render();
	}
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/community_flexiblelayout/controller/class.tx_communityflexiblelayout_controller_startpage_mygroupswidget.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/community_flexiblelayout/controller/class.tx_communityflexiblelayout_controller_startpage_mygroupswidget.php']);
}

?>