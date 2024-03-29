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

require_once(t3lib_extMgm::extPath('community_flexiblelayout').'interfaces/class.tx_communityflexiblelayout_commandinterface.php');
require_once(t3lib_extMgm::extPath('community_flexiblelayout').'classes/class.tx_communityflexiblelayout_layoutmanager.php');

require_once(t3lib_extMgm::extPath('community').'classes/class.tx_community_applicationmanager.php');
require_once(t3lib_extMgm::extPath('community').'classes/class.tx_community_registry.php');
require_once(t3lib_extMgm::extPath('community').'controller/class.tx_community_controller_abstractcommunityapplication.php');
require_once(t3lib_extMgm::extPath('community').'model/class.tx_community_model_usergateway.php');
require_once(t3lib_extMgm::extPath('community').'classes/class.tx_community_accessmanager.php');

/**
 * Show Dashboard Command (model)
 *
 * @author	Frank Nägler <typo3@naegler.net>
 * @package TYPO3
 * @subpackage community_flexiblelayout
 */
class tx_communityflexiblelayout_editDashboardCommand extends tx_community_controller_AbstractCommunityApplication implements tx_communityflexiblelayout_CommandInterface {
	protected $commandName = 'editDashboard';
	/**
	 * @var tx_community_ApplicationManager
	 */
	protected $communityApplicationManager;
	/**
	 * @var tx_community_model_UserGateway
	 */
	protected $userGateway;
	/**
	 * @var tx_community_model_AbstractProfile
	 */
	protected $profile;
	/**
	 * @var tx_community_AccessManager
	 */
	protected $accessManager;
	protected $widgets = array();
	protected $cols = array();
	/**
	 * @var t3lib_cobj
	 */
	public $cObj;

	public function __construct() {
		parent::__construct();
		$this->communityApplicationManager = tx_community_ApplicationManager::getInstance();
		$registry = tx_community_Registry::getInstance('tx_communityflexiblelayout');
		$this->conf = $registry->getConfiguration();
		//$this->configuration = $registry->getConfiguration();
		$this->request = t3lib_div::GParrayMerged('tx_community');
		//$this->userGateway = new tx_community_model_UserGateway();
		$this->accessManager = tx_community_AccessManager::getInstance();

		$localizationManagerClass = t3lib_div::makeInstanceClassName('tx_community_LocalizationManager');
		$this->localizationManager = call_user_func(
			array($localizationManagerClass, 'getInstance'),
			t3lib_extMgm::extPath('community_flexiblelayout') . 'lang/locallang_application.xml',
			$GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_communityflexiblelayout.']
		);
				
		
		$this->name = $this->conf['profileType'];
		
		$this->cObj = t3lib_div::makeInstance('tslib_cObj');
	}
	
	public function execute() {
		$application = $this->communityApplicationManager->getApplication($this->conf['profileType']);
		$widgets = $this->communityApplicationManager->getWidgetsByApplication($this->conf['profileType']);
		$disabledWidgets = t3lib_div::trimExplode(',', $this->conf['disabledWidgets.'][$this->conf['profileType']]);
		if (!is_array($disabledWidgets)) {
			$disabledWidgets = array();
		}
		
		$config = $application->getCommunityTypoScriptConfiguration();
		foreach ($widgets as $widgetName => $widget) {
			$widget->initialize($this->data, $config);
			$widget->setCommunityApplication($application);
			
			if ($widget instanceof tx_community_CommunityApplicationWidget && (!in_array($widgetName, $disabledWidgets))) {
				$this->widgets[$widgetName] = $widget;
			}
		}

		/**
		 * @var tx_communityflexiblelayout_LayoutManager
		 */
		$layoutManager = new tx_communityflexiblelayout_LayoutManager();

		try {
			$this->profile		= tx_community_ProfileFactory::createProfile($this->conf['profileType']);
		} catch (Exception $exception) {
			throw $exception;
		}	
		
		$profileId = $this->profile->getUid();

		$config = $layoutManager->getConfiguration($this->conf['communityID'], $this->conf['profileType'], $profileId);
		$config = unserialize($config);
		if (is_array($config)) {
			foreach($config as $c) {
				$parts = t3lib_div::trimExplode(',', $c);
				$newConfig[$parts[2]] = array(
					'col'	=> $parts[0],
					'pos'	=> $parts[1],
					'id'	=> $parts[2]
				);
			}
			$config = $newConfig;
		} else {
			$config = array();
		}

		foreach ($this->widgets as $widgetName => $widget) {
			$labelKey = 'label_dashboard_'.$this->conf['profileType'].'_'.$widget->getName();
			$label = $this->localizationManager->getLL($labelKey);
			if (strlen($label) > 0) {
				$widget->setLabel($label);
			}
			if (is_array($config[$widgetName])) {
				$this->cols[$config[$widgetName]['col']]['pos'.$config[$widgetName]['pos']] = $widget;
			} else {
				$this->cols[$widget->getLayoutContainer()][] = $widget;
			}
		}
		foreach ($this->cols as $k => $v) {
			ksort($this->cols[$k]);
		}
	}
	
	public function getName() {
		return $this->conf['profileType'];
	}

	public function getCommandName() {
		return $this->commandName;
	}

	public function getWidgetsByCol($col) {
		return $this->cols[$col];
	}

	public function getAllWidgets() {
		return $this->cols;
	}
}
?>