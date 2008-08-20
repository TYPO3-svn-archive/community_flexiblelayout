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
		$containerTemplate = $this->templateEngine->getSubpart('template_container');
		$widgetTemplate = $this->templateEngine->getSubpart('template_widget');
		$cObj = $this->templateEngine->getCObj();
		for ($i=1; $i<=$this->conf['containerCount']; $i++) {
			$marker['CONTAINER_ID'] = "tx-communityflexiblelayout-dashboard-col{$i}";
			$marker['CONTAINER_CLASSES'] = "tx-communityflexiblelayout-dashboard-container";
					
			$widgetsArray = $this->model->getWidgetsByCol($i);
			$widgetCode = '';
			if (is_array($widgetsArray)) {
				foreach ($widgetsArray as $widgetName => $widget) {
					$widgetClasses = array();
					$widgetClasses[] = 'widget';
					$widgetClasses[] = ($widget->isDragable()) ? 'draggable' : 'undraggable';
					$widgetClasses[] = ($widget->isRemovable()) ? 'removable' : '';
					$widgetMarker = array(
						'WIDGET_LABEL'	=> $widget->getLabel(),
						'WIDGET_CONTENT' => $widget->render(),
						'WIDGET_ID' => "tx-communityflexiblelayout-dashboard-widget-{$widget->getID()}",
						'WIDGET_CLASSES' => implode(' ', $widgetClasses)
					);
					
					$widgetCode .= $cObj->substituteMarkerArray(
						$widgetTemplate,
						$widgetMarker,
						'###|###'
					);
				}
			}
			$container = $cObj->substituteSubpart(
				$containerTemplate,
				'###TEMPLATE_WIDGET###',
				$widgetCode
			);
			$containerCode .= $cObj->substituteMarkerArray(
				$container,
				$marker,
				'###|###'
			);
		}
		$widgetsArray = $this->model->getWidgetsByCol(0);
		$clipBoardWidgets = '';
		if (is_array($widgetsArray)) {
			foreach ($widgetsArray as $widgetName => $widget) {
				$widgetClasses = array();
				$widgetClasses[] = 'widget';
				$widgetClasses[] = ($widget->isDragable()) ? 'draggable' : '';
				$widgetClasses[] = ($widget->isRemovable()) ? 'removable' : '';
				$widgetMarker = array(
					'WIDGET_LABEL'	=> $widget->getLabel(),
					'WIDGET_CONTENT' => $widget->render(),
					'WIDGET_ID' => "tx-communityflexiblelayout-dashboard-widget-{$widget->getID()}",
					'WIDGET_CLASSES' => implode(' ', $widgetClasses)
				);
				
				$clipBoardWidgets .= $cObj->substituteMarkerArray(
					$widgetTemplate,
					$widgetMarker,
					'###|###'
				);
			}
		}
		
		$this->templateEngine->addSubpart('template_container', $containerCode);
		$this->templateEngine->addMarker('clipboard_widgets', $clipBoardWidgets);
		$this->templateEngine->addMarker('PAGEID', $GLOBALS['TSFE']->id);
		return $this->templateEngine->render();
	}
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MOlDE]['XCLASS']['ext/community_flexiblelayout/controller/class.tx_communityflexiblelayout_controller_dashboard.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/community_flexiblelayout/controller/class.tx_communityflexiblelayout_controller_dashboard.php']);
}

?>