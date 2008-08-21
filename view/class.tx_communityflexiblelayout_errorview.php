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

require_once(t3lib_extMgm::extPath('community').'classes/class.tx_community_registry.php');

/**
 * Error View
 *
 * @author	Frank Nägler <typo3@naegler.net>
 * @package TYPO3
 * @subpackage community_flexiblelayout
 */
class tx_communityflexiblelayout_ErrorView {
	/**
	 * @var Exception
	 */
	protected $model;
	/**
	 * @var tslib_cObj
	 */
	protected $cObj;

	/**
	 * constructor for class tx_communityflexiblelayout_ShowDashboardView
	 */
	public function __construct($model) {
		$this->model = $model;
		$this->cObj = t3lib_div::makeInstance('tslib_cObj');
		$registry = tx_community_Registry::getInstance('tx_communityflexiblelayout');
		$this->conf = $registry->getConfiguration();
	}

	public function render() {
		$templateCode = $this->cObj->fileResource($this->conf['template']);
		$templateCode = $this->cObj->getSubpart($templateCode, "###TEMPLATE_ERROR_{$this->model->getCode()}###");
		
		$marker = array(
			'###ERR_MESSAGE###'		=> $this->model->getMessage(), 
			'###ERR_CODE###'		=> $this->model->getCode(),
			'###ERR_FILE###'		=> $this->model->getFile(),
			'###ERR_LINE###'		=> $this->model->getLine(),
			'###ERR_TRACE###'		=> $this->model->getTraceAsString()
		);
		return $this->cObj->substituteMarkerArray($templateCode, $marker);
	}
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MOlDE]['XCLASS']['ext/community_flexiblelayout/controller/class.tx_communityflexiblelayout_controller_dashboard.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/community_flexiblelayout/controller/class.tx_communityflexiblelayout_controller_dashboard.php']);
}

?>