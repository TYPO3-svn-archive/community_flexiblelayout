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

require_once($GLOBALS['PATH_community'] . 'classes/viewhelper/class.tx_community_viewhelper_lll.php');
require_once($GLOBALS['PATH_community'] . 'classes/viewhelper/class.tx_community_viewhelper_ts.php');
require_once($GLOBALS['PATH_community'] . 'classes/viewhelper/class.tx_community_viewhelper_link.php');
require_once($GLOBALS['PATH_community'] . 'model/class.tx_community_model_usergateway.php');


/**
 * index view for the edit group application
 *
 * @author	Frank Nägler <typo3@naegler.net>
 * @package TYPO3
 * @subpackage community
 */
class tx_communityflexiblelayout_view_StatusWidget {
	/**
	 * @var tx_community_LocalizationManager
	 */
	protected $llManager;

	public function setTemplateFile($templateFile) {
		$this->templateFile = $templateFile;
	}

	public function setLanguageKey($languageKey) {
		$this->languageKey = $languageKey;
	}
	
	public function setUserModel($userModel) {
		$this->userModel = $userModel;
	}
	
	public function setOpenFriendRequestCount($counter) {
		$this->openFriendRequestCount = $counter;
	}
	
	public function setDocuementsSubpart($subpart) {
		$this->documentsSubpart = $subpart;
	}
	
	public function render() {
		$llMangerClass = t3lib_div::makeInstanceClassName('tx_community_LocalizationManager');
		$this->llManager = call_user_func(array($llMangerClass, 'getInstance'), 'EXT:community_flexiblelayout/lang/locallang_statuswidget.xml',	$GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_communityflexiblelayout.']);

		$templateClass = t3lib_div::makeInstanceClassName('tx_community_Template');
		$template = new $templateClass(
			t3lib_div::makeInstance('tslib_cObj'),
			$this->templateFile,
			'status_widget'
		);
		/* @var $template tx_community_Template */

		$template->addViewHelper(
			'lll',
			'tx_community_viewhelper_Lll',
			array(
				'languageFile' => 'EXT:community_flexiblelayout/lang/locallang_statuswidget.xml',
				'llKey'        => $this->languageKey
			)
		);

		$template->addViewHelper(
			'ts',
			'tx_community_viewhelper_Ts'
		);

		$template->addViewHelper(
			'link',
			'tx_community_viewhelper_Link'
		);
		
		$template->addVariable('user', $this->userModel);
		$template->addMarker('documents_status', $this->renderDocumentsStatus());
		$template->addMarker('friendrequestcounter', $this->openFriendRequestCount);
		$template->addMarker('page_id', $GLOBALS['TSFE']->id);
		//$template->addLoop('groups', 'group', $this->groups);

		return $template->render();
	}
	
	protected function renderDocumentsStatus() {
		$llMangerClass = t3lib_div::makeInstanceClassName('tx_community_LocalizationManager');
		$this->llManager = call_user_func(array($llMangerClass, 'getInstance'), 'EXT:community_flexiblelayout/lang/locallang_statuswidget.xml',	$GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_communityflexiblelayout.']);

		$templateClass = t3lib_div::makeInstanceClassName('tx_community_Template');
		$template = new $templateClass(
			t3lib_div::makeInstance('tslib_cObj'),
			$this->templateFile,
			$this->documentsSubpart
		);
				
		return $template->render();
	}
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/community/view/listgroups/class.tx_communityflexiblelayout_view_statuswidget.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/community/view/listgroups/class.tx_communityflexiblelayout_view_statuswidget.php']);
}

?>