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
require_once(t3lib_extMgm::extPath('community_flexiblelayout') . 'view/class.tx_communityflexiblelayout_view_coaspecialwidget.php');

/**
 * content widget for the persoal startpage
 *
 * @author	Frank Nägler <typo3@naegler.net>
 * @package TYPO3
 * @subpackage community
 */
class tx_communityflexiblelayout_controller_CoaspecialWidget extends tx_community_controller_AbstractCommunityApplicationWidget {

	public function __construct() {
		parent::__construct();
		$this->localizationManager = tx_community_LocalizationManager::getInstance('EXT:community_flexiblelayout/lang/locallang_application.xml', $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_communityflexiblelayout.']);
		
		$this->name     = 'coaspecialWidget';
		// $this->label    = $this->localizationManager->getLL('label_'.$this->name);
		if($GLOBALS['TSFE']->fe_user->user['tx_communityflexiblelayout_lotteryonly']){
			$this->label    = $this->localizationManager->getLL('label_coaspecialWidgetLottery');
		}

		$this->draggable = false;
		$this->removable = false;
		$this->position  = 4;
		
	}
	
	/**
	 * the default action for this widget,
	 *
	 * @return	string	the view's output
	 */
	public function indexAction() {
		$content = '';

		$this->configuration = $this->getConfiguration();
		if ($uid = $this->configuration['content.']['source']) {
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
				'header',
				$this->configuration['content.']['tables'],
				'uid = ' . $uid
			);
			$this->label = $res[0]['header'];
		}
		
		$view = t3lib_div::makeInstance('tx_communityflexiblelayout_view_CoaspecialWidget');
		$view->setTemplateFile($this->configuration['applications.'][$this->getCommunityApplication()->getName().'.']['widgets.'][$this->name.'.']['templateFile']);
		$view->setLanguageKey($this->communityApplication->LLkey);

		$content = $view->render();
		
		return $content;
	}
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/community_flexiblelayout/controller/class.tx_communityflexiblelayout_controller_contentwidget.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/community_flexiblelayout/controller/class.tx_communityflexiblelayout_controller_contentwidget.php']);
}

?>