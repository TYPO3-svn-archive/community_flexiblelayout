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
 * show Dashbaord View
 *
 * @author	Frank Nägler <typo3@naegler.net>
 * @package TYPO3
 * @subpackage community_flexiblelayout
 */
class tx_communityflexiblelayout_ShowDashboardView {
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
		$templateCode = $this->cObj->getSubpart($templateCode, '###TEMPLATE_SHOWDASHBOARD###');
		$containerTemplate = $this->cObj->getSubpart($templateCode, '###TEMPLATE_CONTAINER###');
		$widgetTemplate = $this->cObj->getSubpart($templateCode, '###TEMPLATE_WIDGET###');
		if (isset($this->conf['fixColumn'])) {
			$this->conf['containerCount'] = 1;
		}
		for ($i=1; $i<=$this->conf['containerCount']; $i++) {
			$currentColumn = (isset($this->conf['fixColumn'])) ? $this->conf['fixColumn'] : $i;
			$containerClasses = array();
			$containerClasses[] = 'container';
			$containerClasses[] = $this->conf['containerClass'];
			$containerClasses[] = ($this->conf['containerConfig.'][$currentColumn.'.']['alternativClassName']) ? $this->conf['containerConfig.'][$currentColumn.'.']['alternativClassName'] : '';
				
			$marker['CONTAINER_ID'] = "tx-communityflexiblelayout-dashboard-col{$currentColumn}";
			$marker['CONTAINER_CLASSES'] = implode(' ', $containerClasses);
				
			$widgetsArray = $this->model->getWidgetsByCol($currentColumn);
			$widgetCode = '';
			if (is_array($widgetsArray)) {
				foreach ($widgetsArray as $widgetName => $widget) {
					$widgetClasses = array();
					$widgetClasses[] = 'widget';
					//$widgetClasses[] = ($widget->isDraggable()) ? 'draggable' : '';
					//$widgetClasses[] = ($widget->isRemovable()) ? 'removable' : '';
					$widgetClasses[] = ($widget->getCssClass()) ? $widget->getCssClass() : '' ;
					$widgetMarker = array(
						'WIDGET_LABEL'	=> $widget->getLabel(),
						'WIDGET_CONTENT' => $widget->execute(),
						'WIDGET_ID' => "tx-communityflexiblelayout-dashboard-widget-{$widget->getName()}",
						'WIDGET_CLASSES' => implode(' ', $widgetClasses)
					);
						
					$tmpWidgetCode = $this->cObj->substituteMarkerArray(
						$widgetTemplate,
						$widgetMarker,
						'###|###'
					);
					
					$widgetCode .= $this->cObj->stdWrap($tmpWidgetCode, $this->conf['containerConfig.'][$currentColumn.'.']['widgets.'][$widget->getName().'.']['stdWrap.']);
				}
			}
			$container = $this->cObj->substituteSubpart(
				$containerTemplate,
				'###TEMPLATE_WIDGET###',
				$widgetCode
			);

			$tmpContainerCode = $this->cObj->substituteMarkerArray(
				$container,
				$marker,
				'###|###'
			);
			
			$containerCode .= $this->cObj->stdWrap($tmpContainerCode, $this->conf['containerConfig.'][$currentColumn.'.']['stdWrap.']);
			
		
		}
		$content = $this->cObj->substituteSubpart($templateCode, '###TEMPLATE_CONTAINER###', $containerCode);

		$marker = array(
			'###PAGEID###' => $GLOBALS['TSFE']->id
		);
		return $this->cObj->substituteMarkerArray($content, $marker);
	}
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MOlDE]['XCLASS']['ext/community_flexiblelayout/controller/class.tx_communityflexiblelayout_controller_dashboard.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/community_flexiblelayout/controller/class.tx_communityflexiblelayout_controller_dashboard.php']);
}

?>