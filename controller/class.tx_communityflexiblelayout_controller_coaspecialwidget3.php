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

/**
 * content widget for the persoal startpage
 *
 * @author	Frank Nägler <typo3@naegler.net>
 * @package TYPO3
 * @subpackage community
 */
class tx_communityflexiblelayout_controller_CoaspecialWidget3 extends tx_communityflexiblelayout_controller_CoaspecialWidget {

	public function __construct() {
		parent::__construct();
		$this->name     = 'coaspecialWidget3';
		// $this->label    = $this->localizationManager->getLL('label_'.$this->name);
		$uid = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_community.']['applications.']['StartPage.']['widgets.']['coaspecialWidget3.']['content.']['source'];
		if ($uid > 0) {
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'header',
				'tt_content',
				'uid = ' . $uid
			);
			if ($GLOBALS['TYPO3_DB']->sql_num_rows($res) > 0) {
				$data = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
				$this->label = $data['header'];
			}
		}
	}
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/community_flexiblelayout/controller/class.tx_communityflexiblelayout_controller_contentwidget3.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/community_flexiblelayout/controller/class.tx_communityflexiblelayout_controller_contentwidget3.php']);
}

?>