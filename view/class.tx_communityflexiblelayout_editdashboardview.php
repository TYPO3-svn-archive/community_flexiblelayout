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

require_once $GLOBALS['PATH_donation'] . 'classes/class.tx_donation_Registry.php';
require_once(t3lib_extMgm::extPath('community_flexiblelayout').'classes/class.tx_communityflexiblelayout_templateengineadapter.php');

/**
 * show Dashbaord View
 *
 * @author	Frank Nägler <typo3@naegler.net>
 * @package TYPO3
 * @subpackage community_flexiblelayout
 */
class tx_communityflexiblelayout_EditDashboardView {
	/**
	 * @var tx_communityflexiblelayout_TemplateEngineAdapter
	 */
	protected $templateEngine;
	protected $model;

	/**
	 * constructor for class tx_communityflexiblelayout_ShowDashboardView
	 */
	public function __construct($model) {
		$this->model = $model;
		$registry = tx_donation_Registry::getInstance('');
		$this->conf = $registry->get('configuration');
		$this->templateEngine = new tx_communityflexiblelayout_TemplateEngineAdapter($this->conf['template'], 'TEMPLATE_'.$this->model->getCommandName());
	}

	public function render() {
		$widgetsArray = $this->model->getAllWidgets();
		$this->templateEngine->addMarker("CONTAINER1", '');
		$this->templateEngine->addMarker("CONTAINER2", '');
		$this->templateEngine->addMarker("CONTAINER3", '');
		$this->templateEngine->addMarker("CONTAINER4", '');
		$this->templateEngine->addMarker("CONTAINER5", '');
		$this->templateEngine->addMarker("CONTAINER6", '');
		$this->templateEngine->addMarker("CONTAINER7", '');
		$this->templateEngine->addMarker("CONTAINER8", '');
		$this->templateEngine->addMarker("CONTAINER9", '');
		$this->templateEngine->addMarker("CONTAINER10", '');
		foreach ($widgetsArray as $widgetArray) {
			$container = '';
			foreach ($widgetArray as $widget) {
				$container .= '
					<div class="widget">
						<div class="label">'.$widget->getLabel().'</div>
						<div class="content">'.$widget->render().'</div>
					</div>
				';
			}
			$number = $i+1;
			$this->templateEngine->addMarker("CONTAINER{$number}", $container);
		}
		return $this->templateEngine->render();
	}
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MOlDE]['XCLASS']['ext/community_flexiblelayout/controller/class.tx_communityflexiblelayout_controller_dashboard.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/community_flexiblelayout/controller/class.tx_communityflexiblelayout_controller_dashboard.php']);
}

?>